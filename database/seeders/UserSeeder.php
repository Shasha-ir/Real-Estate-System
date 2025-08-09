<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sellerRoleId = Role::where('name', 'seller')->first()->id;
        $buyerRoleId = Role::where('name', 'buyer')->first()->id;

        // Sellers
        $sellers = [
            'Michael Scofield',
            'Edward Cullen',
            'Bellamy Blake',
            'Ryohei Arisu',
            'Merlin Ambrosius',
            'Hermione Granger',
            'Wei Wuxian',
            'Lan Wangji',
            'Clarke Griffin',
            'Enola Holmes'
        ];

        $sellerCounter = 1;
        foreach ($sellers as $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@mail.com',
                'username' => strtolower(str_replace(' ', '_', $name)),
                'custom_id' => 'S' . str_pad($sellerCounter++, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role_id' => $sellerRoleId,
            ]);
        }

        // Buyers
        $buyers = [
            'Arya Stark',
            'Luna Lovegood',
            'Ron Weasly',
            'Fushiguro Megumi',
            'Itadori Yuji'
        ];

        $buyerCounter = 1;
        foreach ($buyers as $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@mail.com',
                'username' => strtolower(str_replace(' ', '_', $name)),
                'custom_id' => 'B' . str_pad($buyerCounter++, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role_id' => $buyerRoleId,
            ]);
        }
    }
}
