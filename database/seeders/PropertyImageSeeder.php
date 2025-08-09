<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyImage;
use App\Models\Property;

class PropertyImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $properties = Property::all();
        $i = 1;

        foreach ($properties as $property) {
            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => "images/image{$i}.jpg",
            ]);
            $i++;
        }
    }
}
