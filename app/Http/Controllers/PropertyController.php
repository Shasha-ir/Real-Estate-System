<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;


class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $properties = DB::table('properties')
            ->leftJoin('categories', 'categories.id', '=', 'properties.category_id')
            ->select('properties.*', 'categories.name as category_name')
            ->where('properties.is_available', true)
            ->orderByDesc('properties.created_at')
            ->limit(60)
            ->get();

        return view('properties.index', compact('properties'));
    }

    public function residential()
    {
        $catId = DB::table('categories')->whereRaw('LOWER(name)=?', ['residential'])->value('id');
        $properties = DB::table('properties')
            ->when($catId, fn($q) => $q->where('category_id', $catId))
            ->where('is_available', true)
            ->orderByDesc('created_at')
            ->limit(60)
            ->get();

        return view('properties.residential', compact('properties'));
    }


public function commercial()
    {
        $catId = DB::table('categories')->whereRaw('LOWER(name)=?', ['commercial'])->value('id');
        $properties = DB::table('properties')
            ->when($catId, fn($q) => $q->where('category_id', $catId))
            ->where('is_available', true)
            ->orderByDesc('created_at')
            ->limit(60)
            ->get();

        return view('properties.commercial', compact('properties'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($propertyId)
    {
        $property = DB::table('properties')
            ->leftJoin('categories', 'categories.id', '=', 'properties.category_id')
            ->leftJoin('users', 'users.id', '=', 'properties.user_id')
            ->select('properties.*', 'categories.name as category_name', 'users.username as seller_username')
            ->where('properties.id', $propertyId)
            ->first();

        if (!$property) {
            return redirect()->route('properties.index')->with('error', 'Property not found.');
        }

        $images = DB::table('property_images')->where('property_id', $propertyId)->pluck('image_path');

        return view('properties.show', compact('property', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $propertyId)
    {
        $user = $request->user();

        // 1) Fetch property
        $property = DB::table('properties')->where('id', $propertyId)->first();
        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        // 2) Owner check
        if ((int) $property->user_id !== (int) $user->id) {
            return back()->with('error', 'You are not allowed to delete this property.');
        }

        // 3) Block delete if any reservations exist
        $hasReservations = DB::table('reservations')->where('property_id', $propertyId)->exists();
        if ($hasReservations) {
            return back()->with('error', 'Cannot delete a property that has reservations.');
        }

        // 4) Delete image files + rows, then property
        $images = DB::table('property_images')->where('property_id', $propertyId)->get();

        DB::beginTransaction();
        try {
            foreach ($images as $img) {
                // stored like 'storage/properties/abc.jpg' → convert to public disk path
                $publicPath = is_string($img->image_path) ? preg_replace('#^storage/#', '', $img->image_path) : null;
                if ($publicPath) {
                    Storage::disk('public')->delete($publicPath);
                }
            }

            DB::table('property_images')->where('property_id', $propertyId)->delete();
            DB::table('properties')->where('id', $propertyId)->delete();

            if (Schema::hasTable('audit_logs')) {
                DB::table('audit_logs')->insert([
                    'user_id' => $user->id,
                    'entity' => 'property',
                    'entity_id' => $propertyId,
                    'action' => 'delete',
                    'details' => json_encode(['source' => 'seller_dashboard']),
                    'created_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete property: ' . $e->getMessage());
        }

        // 5) Back to seller dashboard
        $dash = app('router')->has('dashboard.seller')
            ? route('dashboard.seller')
            : (app('router')->has('seller.dashboard') ? route('seller.dashboard') : url('/dashboard/seller'));

        return redirect($dash)->with('success', 'Property deleted successfully.');
    }
}
