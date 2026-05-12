<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Location;

class DriveXCarsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a location exists
        $location = Location::first();
        if (!$location) {
            $location = Location::create(['city' => 'Los Angeles', 'address' => '123 Luxury Ave']);
        }

        $demoCars = [
            ['name' => 'Luxury Sedan', 'color' => 'Black', 'price_per_day' => 55000, 'image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&q=80', 'type' => 'Sedan'],
            ['name' => 'Sports Coupe', 'color' => 'Black', 'price_per_day' => 55000, 'image' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800&q=80', 'type' => 'Coupe'],
            ['name' => 'Luxury SUV', 'color' => 'Black', 'price_per_day' => 55000, 'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800&q=80', 'type' => 'SUV'],
            ['name' => 'Convertible', 'color' => 'Black', 'price_per_day' => 55000, 'image' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&q=80', 'type' => 'Convertible'],
            ['name' => 'Hatchback', 'color' => 'Black', 'price_per_day' => 55000, 'image' => 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800&q=80', 'type' => 'Hatchback'],
            ['name' => 'Electric SUV', 'color' => 'Black', 'price_per_day' => 55000, 'image' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=800&q=80', 'type' => 'SUV'],
        ];

        // Clear existing cars to avoid duplicates if they want it perfectly matching the 6
        Car::truncate();

        foreach ($demoCars as $carData) {
            Car::create(array_merge($carData, [
                'location_id' => $location->id,
                'is_available' => true
            ]));
        }
    }
}
