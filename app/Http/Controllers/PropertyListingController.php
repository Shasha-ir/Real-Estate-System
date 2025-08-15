<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\PropertyUnderReviewMail;

class PropertyListingController extends Controller
{

    private function sellerDashboardUrl(): string
    {
        if (\Illuminate\Support\Facades\Route::has('dashboard.seller')) {
            return route('dashboard.seller');
        }
        if (\Illuminate\Support\Facades\Route::has('seller.dashboard')) {
            return route('seller.dashboard');
        }
        return url('/dashboard/seller');
    }


    public function preview(Request $request)
    {
        // Validate listing inputs (kept simple & flexible)
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:residential,commercial'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Store image now so we don’t lose it between pages
        $imagePath = null;
        if ($request->hasFile('image')) {
            // ensure you've run: php artisan storage:link
            $imagePath = $request->file('image')->store('properties', 'public');
        }

        // Keep draft in session (PRG-friendly)
        session([
            'listing_draft' => [
                'title' => $validated['title'],
                'price' => $validated['price'],
                'location' => $validated['location'] ?? null,
                'type' => $validated['type'] ?? null,
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath, // e.g. storage/properties/...
            ]
        ]);

        return redirect()->route('properties.checkout');
    }
    public function checkout(Request $request)
    {
        $draft = session('listing_draft');
        if (!$draft) {
            // reuse the helper we added earlier
            return redirect($this->sellerDashboardUrl())->with('error', 'No listing draft found.');
        }

        return view('properties.checkout', [
            'draft' => $draft,
            'fee' => 1000,
        ]);
    }


    public function confirm(Request $request)
    {
        // 1) Validate mock card details
        $request->validate([
            'card_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits_between:13,19'],
            'expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'], // MM/YY
            'cvc' => ['required', 'digits_between:3,4'],
        ], [
            'expiry.regex' => 'Expiry must be in MM/YY format.',
        ]);

        // Basic expiry sanity check (not a real payment!)
        [$mm, $yy] = explode('/', $request->expiry);
        $mm = (int) $mm;
        $yy = (int) $yy + 2000;
        $endOfMonth = \Carbon\Carbon::create($yy, $mm, 1)->endOfMonth();
        if (now()->greaterThan($endOfMonth)) {
            return back()->withErrors(['expiry' => 'Card is expired.'])->withInput();
        }

        // 2) Get the draft from session
        $draft = session('listing_draft');
        if (!$draft) {
            return redirect($this->sellerDashboardUrl())->with('error', 'No listing draft found.');
        }

        $user = $request->user();

        // 3) Resolve category_id (‘Residential’ or ‘Commercial’ -> categories.name)
        $typeLabel = $draft['type'] === 'commercial' ? 'Commercial' : 'Residential';
        $categoryId = \Illuminate\Support\Facades\DB::table('categories')
            ->whereRaw('LOWER(name) = ?', [strtolower($typeLabel)])
            ->value('id');

        if (!$categoryId) {
            // create if missing (safe, additive)
            $categoryId = \Illuminate\Support\Facades\DB::table('categories')->insertGetId([
                'name' => $typeLabel,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4) Insert the property (pending review -> is_available = false)
        try {
            $propertyId = \Illuminate\Support\Facades\DB::table('properties')->insertGetId([
                'title' => $draft['title'],
                'description' => $draft['description'] ?? '',
                'price' => $draft['price'],
                'location' => $draft['location'] ?? '',
                'user_id' => $user->id,         // seller
                'category_id' => $categoryId,
                'is_available' => false,             // NOT public until admin approves
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // tidy up stored file if any
            if (!empty($draft['image_path'])) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($draft['image_path']);
            }
            return redirect($this->sellerDashboardUrl())
                ->with('error', 'Failed to save listing: ' . $e->getMessage());
        }

        // 5) Save image to property_images (if uploaded in preview step)
        if (!empty($draft['image_path'])) {
            try {
                \Illuminate\Support\Facades\DB::table('property_images')->insert([
                    'property_id' => $propertyId,
                    'image_path' => 'storage/' . $draft['image_path'], // web-accessible after storage:link
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // image insertion failed — keep property but add a gentle note
                // (Optional) log this if you want
            }
        }

        // 6) Audit log (optional but nice; uses your audit_logs table if present)
        if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
            \Illuminate\Support\Facades\DB::table('audit_logs')->insert([
                'user_id' => $user->id,
                'entity' => 'property',
                'entity_id' => $propertyId,
                'action' => 'submit_for_review',
                'details' => json_encode([
                    'source' => 'seller_dashboard',
                    'listing_fee_mock' => 1000,
                ]),
                'created_at' => now(),
            ]);
        }

        // 7) Email the seller (uses your Gmail SMTP config)
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\PropertyUnderReviewMail($draft['title']));
        } catch (\Throwable $e) {
            // Don’t fail the flow if email can’t send
            session()->flash('info', 'Email could not be sent right now: ' . $e->getMessage());
        }

        // 8) Clear draft and redirect
        session()->forget('listing_draft');

        return redirect($this->sellerDashboardUrl())
            ->with('success', 'Your property has been submitted and is pending admin review. We will notify you within 5 working days.');
    }


    private function pickTable(array $candidates): ?string
    {
        foreach ($candidates as $t) {
            if (Schema::hasTable($t))
                return $t;
        }
        return null;
    }

    private function pickColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c))
                return $c;
        }
        return null;
    }
}
