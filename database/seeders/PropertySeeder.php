<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use App\Models\Category;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $residentialId = Category::where('name', 'Residential')->first()->id;
        $commercialId = Category::where('name', 'Commercial')->first()->id;
        $sellers = User::all();

        // Residential properties (1–6)
        foreach (range(1, 6) as $i) {
            Property::create([
                'title' => "Property #$i",
                'description' => "Residential property #$i description.",
                'price' => rand(100000, 400000),
                'location' => "Colombo " . rand(1, 15),
                'user_id' => $sellers[$i % count($sellers)]->id,
                'category_id' => $residentialId,
                'is_available' => true,
            ]);
        }

        // Commercial properties (7–12)
        foreach (range(7, 12) as $i) {
            Property::create([
                'title' => "Commercial Unit #$i",
                'description' => "Commercial property #$i description.",
                'price' => rand(300000, 700000),
                'location' => "Kandy " . rand(1, 15),
                'user_id' => $sellers[$i % count($sellers)]->id,
                'category_id' => $commercialId,
                'is_available' => true,
            ]);
        }
    }
}
