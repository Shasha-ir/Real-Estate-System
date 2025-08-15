<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SellerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Detect tables/columns gently so we don't break your schema
        $propsTable  = $this->pickTable(['properties', 'listings', 'property']);
        $resTable    = $this->pickTable(['reservations', 'reservation', 'bookings']);

        $stats = [
            'total_listings'       => 0,
            'properties_reserved'  => 0,
            'total_revenue'        => 0,
        ];

        $listings = collect();

        if ($propsTable) {
            $fkSeller   = $this->pickColumn($propsTable, ['seller_id', 'user_id', 'owner_id']);
            $colTitle   = $this->pickColumn($propsTable, ['title', 'name', 'headline', 'address']);
            $colPrice   = $this->pickColumn($propsTable, ['price', 'listed_price', 'amount']);
            $colStatus  = $this->pickColumn($propsTable, ['status', 'is_active']);
            $colCreated = $this->pickColumn($propsTable, ['created_at', 'listed_at', 'updated_at']);
            $colAvailable = $this->pickColumn($propsTable, ['is_available','is_active','active']);


            // Base query: this seller's properties
            $q = DB::table($propsTable)->where($fkSeller, $user->id);

            $select = ["$propsTable.id as id"];
            if ($colTitle)   $select[] = "$propsTable.$colTitle as title";
            if ($colPrice)   $select[] = "$propsTable.$colPrice as price";
            if ($colStatus)  $select[] = "$propsTable.$colStatus as pstatus";
            if ($colCreated) $select[] = "$propsTable.$colCreated as created_at";
            if ($colAvailable) $select[] = "$propsTable.$colAvailable as is_available";

            

            // If reservations table exists, attach reservation counts per property
            if ($resTable) {
                $resPropFK   = $this->pickColumn($resTable, ['property_id', 'listing_id']);
                $resStatus   = $this->pickColumn($resTable, ['status', 'payment_status']);
                $resAmount = $this->pickColumn($resTable, ['fee', 'reservation_fee', 'amount', 'paid_amount', 'total', 'price']);

                // Left join & aggregate reservation counts
                $q->leftJoin($resTable, "$resTable.$resPropFK", '=', "$propsTable.id")
                  ->selectRaw("COUNT($resTable.id) as reserved_count");

                // Stats: total revenue from confirmed/paid reservations
                $statusValues = ['confirmed','paid','success','completed'];
                $revenueQuery = DB::table($propsTable)
                    ->join($resTable, "$resTable.$resPropFK", '=', "$propsTable.id")
                    ->where("$propsTable.$fkSeller", $user->id);

                if ($resStatus) {
                    $revenueQuery->whereIn(DB::raw("LOWER($resTable.$resStatus)"), $statusValues);
                }

                $stats['total_revenue'] = $resAmount
                    ? (int) $revenueQuery->sum("$resTable.$resAmount")
                    : 0;

                // Properties with at least one reservation
                $reservedCountQuery = DB::table($propsTable)
                    ->join($resTable, "$resTable.$resPropFK", '=', "$propsTable.id")
                    ->where("$propsTable.$fkSeller", $user->id)
                    ->distinct("$propsTable.id");

                $stats['properties_reserved'] = (int) $reservedCountQuery->count("$propsTable.id");
            }

            // Execute listings query
            $listings = $q->groupBy("$propsTable.id");
            if ($colCreated) $listings->orderBy($colCreated, 'desc');
            $listings = $listings->select($select)->limit(100)->get();

            $stats['total_listings'] = $listings->count();
        }

        return view('dashboard.seller', [
            'user'     => $user,      // (view also uses auth() fallback)
            'stats'    => $stats,
            'listings' => $listings,
        ]);
    }

    private function pickTable(array $candidates): ?string
    {
        foreach ($candidates as $t) {
            if (Schema::hasTable($t)) return $t;
        }
        return null;
    }

    private function pickColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) return $c;
        }
        return null;
    }
}
