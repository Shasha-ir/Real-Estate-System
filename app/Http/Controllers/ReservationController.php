<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * GET /reserve/{property}
     * Show the reserve (mock checkout) page for a property.
     */
    public function create(Request $request, $propertyId)
    {
        $property = DB::table('properties')->where('id', $propertyId)->first();
        if (!$property) {
            return redirect()->route('properties.index')->with('error', 'Property not found.');
        }

        // Mock reservation fee: 5% of price, minimum Rs. 25,000
        $fee = max(25000, (int) round(($property->price ?? 0) * 0.05));

        return view('reservations.checkout', [
            'property' => $property,
            'fee' => $fee,
        ]);
    }

    /**
     * POST /reserve/{property}
     * Validate mock card, create reservation + payment, redirect to buyer dashboard.
     */
    public function store(Request $request, $propertyId)
    {
        // Validate mock card fields
        $request->validate([
            'card_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits_between:13,19'],
            'expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'], // MM/YY
            'cvc' => ['required', 'digits_between:3,4'],
        ], [
            'expiry.regex' => 'Expiry must be in MM/YY format.',
        ]);

        // Expiry must be in the future
        [$mm, $yy] = explode('/', $request->expiry);
        $mm = (int) $mm;
        $yy = (int) $yy + 2000;
        $endOfMonth = Carbon::create($yy, $mm, 1)->endOfMonth();
        if (now()->greaterThan($endOfMonth)) {
            return back()->withErrors(['expiry' => 'Card is expired.'])->withInput();
        }

        $property = DB::table('properties')->where('id', $propertyId)->first();
        if (!$property) {
            return redirect()->route('properties.index')->with('error', 'Property not found.');
        }

        $buyerId = $request->user()->id;
        $fee = max(25000, (int) round(($property->price ?? 0) * 0.05));

        // Create reservation
        $reservationId = DB::table('reservations')->insertGetId([
            'property_id' => $propertyId,
            'buyer_id' => $buyerId,
            'fee' => $fee,
            'status' => 'confirmed',
            'reserved_at' => now(),
        ]);


        // Record mock payment
        DB::table('payments')->insert([
            'reservation_id' => $reservationId,
            'amount' => $fee,
            'method' => 'card-mock',
            'status' => 'paid',
            'paid_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dashboard.buyer')
            ->with('success', 'Reservation confirmed. Receipt is now available in your dashboard.');
    }

    /**
     * OPTIONAL: POST /reserve/{reservation}/cancel
     * Simple cancel that ensures ownership.
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        if ((int) $reservation->buyer_id !== (int) $request->user()->id) {
            abort(403);
        }

        DB::table('reservations')
            ->where('id', $reservation->id)
            ->update([
                'status' => 'canceled',
                'updated_at' => now(),
            ]);

        return back()->with('message', 'Reservation canceled.');
    }
}
