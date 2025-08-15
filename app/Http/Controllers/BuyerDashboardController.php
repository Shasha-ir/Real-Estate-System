<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BuyerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // whether sellers have a phone column
        $hasPhone = Schema::hasColumn('users', 'phone');

        $q = DB::table('reservations')
            ->join('properties', 'properties.id', '=', 'reservations.property_id')
            ->join('users as sellers', 'sellers.id', '=', 'properties.user_id')
            ->where('reservations.buyer_id', $user->id)
            ->select(
                'reservations.id as reservation_id',
                'reservations.status',
                'reservations.fee',
                'reservations.reserved_at',
                'properties.id as property_id',
                'properties.title',
                'properties.location',
                'properties.price',
                'sellers.username as seller_username',
                'sellers.email as seller_email',
            );
        // add phone if the column exists; otherwise return NULL as phone
        if ($hasPhone) {
            $q->addSelect('sellers.phone');
        } else {
            $q->addSelect(DB::raw('NULL as phone'));
        }

        $myReservations = $q->orderByDesc('reservations.reserved_at')->get();

        // --- Choose tables/columns safely (works with your existing schema names) ---
        $reservationsTable = $this->pickTable(['reservations', 'reservation', 'bookings']);
        $propsTable = $this->pickTable(['properties', 'listings', 'property']);
        $favoritesTable = $this->pickTable(['favorites', 'wishlists', 'wishlist']);

        $reservations = collect();
        $stats = [
            'total_reservations' => 0,
            'total_paid' => 0,
            'favorites_count' => 0,
        ];

        if ($reservationsTable) {
            $fkBuyer = $this->pickColumn($reservationsTable, ['buyer_id', 'user_id', 'customer_id']);
            $colAmount = $this->pickColumn($reservationsTable, ['fee', 'reservation_fee', 'amount', 'paid_amount', 'price', 'total']);

            $colStatus = $this->pickColumn($reservationsTable, ['status', 'payment_status']);
            $colDate = $this->pickColumn($reservationsTable, ['reserved_at', 'created_at', 'updated_at']);
            $colPropFK = $this->pickColumn($reservationsTable, ['property_id', 'listing_id']);

            $query = DB::table($reservationsTable)->where($fkBuyer, $user->id);

            $select = ["$reservationsTable.id as id"];
            if ($colDate)
                $select[] = "$reservationsTable.$colDate as reserved_at";
            if ($colAmount)
                $select[] = "$reservationsTable.$colAmount as amount";
            if ($colStatus)
                $select[] = "$reservationsTable.$colStatus as status";
            if ($colPropFK)
                $select[] = "$reservationsTable.$colPropFK as property_id";

            // Optional join with properties/listings for nicer labels/prices
            $propTitle = $propsTable ? $this->pickColumn($propsTable, ['title', 'name', 'address', 'headline']) : null;
            $propPrice = $propsTable ? $this->pickColumn($propsTable, ['price', 'listed_price', 'amount']) : null;

            if ($propsTable && $colPropFK) {
                $query->leftJoin($propsTable, "$propsTable.id", '=', "$reservationsTable.$colPropFK");
                if ($propTitle)
                    $select[] = "$propsTable.$propTitle as property_title";
                if ($propPrice)
                    $select[] = "$propsTable.$propPrice as property_price";
            }

            if ($colDate)
                $query->orderBy($colDate, 'desc');

            $reservations = $query->select($select)->limit(50)->get();

            $stats['total_reservations'] = $reservations->count();
            $stats['total_paid'] = $colAmount
                ? (int) $reservations->sum('amount')
                : 0;
        }

        if ($favoritesTable) {
            $fkFavUser = $this->pickColumn($favoritesTable, ['user_id', 'buyer_id']);
            if ($fkFavUser) {
                $stats['favorites_count'] = DB::table($favoritesTable)->where($fkFavUser, $user->id)->count();
            }
        }

        return view('dashboard.buyer', [
            'user' => $user,
            'reservations' => $reservations,
            'stats' => $stats,
            'myReservations' => $myReservations,
            'hasSellerPhone' => $hasPhone,
        ]);
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
