<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarCategory;

class CarCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'High-performance machines built for thrill',
                'icon' => '🏎️',
                'image_url' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=600',
            ],
            [
                'name' => 'SUV',
                'slug' => 'suv',
                'description' => 'Spacious and powerful for every terrain',
                'icon' => '🚙',
                'image_url' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=600',
            ],
            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'description' => 'Premium comfort and elite craftsmanship',
                'icon' => '💎',
                'image_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600',
            ],
            [
                'name' => 'Electric',
                'slug' => 'electric',
                'description' => 'Future-forward zero emission driving',
                'icon' => '⚡',
                'image_url' => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=600',
            ],
            [
                'name' => 'Convertible',
                'slug' => 'convertible',
                'description' => 'Open-top freedom on every road',
                'icon' => '🌤️',
                'image_url' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=600',
            ],
            [
                'name' => 'Hatchback',
                'slug' => 'hatchback',
                'description' => 'Compact, smart and city-ready',
                'icon' => '🚗',
                'image_url' => 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=600',
            ],
        ];

        foreach ($categories as $category) {
            CarCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
