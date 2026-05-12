<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('car_categories')->onDelete('set null');
            $table->string('car_name')->nullable();
            $table->string('brand')->nullable();
            $table->integer('year')->nullable();
            $table->integer('seats')->nullable()->default(5);
            $table->string('transmission')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('mileage')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id', 'car_name', 'brand', 'year', 
                'seats', 'transmission', 'fuel_type', 'mileage'
            ]);
        });
    }
};
