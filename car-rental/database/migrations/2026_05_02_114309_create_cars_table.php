<?php // allow PHP code in this migration file so Laravel can execute it
use Illuminate\Database\Migrations\Migration; // import Migration so we can define the up/down steps
use Illuminate\Database\Schema\Blueprint; // import Blueprint so we can describe table columns
use Illuminate\Support\Facades\Schema; // import Schema so we can create and drop tables
return new class extends Migration { // create an anonymous migration class for this table
    public function up(): void { // define the build step that creates the cars table
        Schema::create('cars', function (Blueprint $table) { // start defining the cars table
            $table->id(); // add an auto-incrementing primary key so each car is unique
            $table->string('type'); // store the car type like sedan or SUV for filtering
            $table->foreignId('location_id')->constrained()->cascadeOnDelete(); // link each car to a location and remove cars if the location is deleted
            $table->decimal('price_per_day', 8, 2); // store the daily rental price with cents
            $table->boolean('is_available')->default(true); // track if the car can be booked right now
            $table->timestamps(); // add created_at and updated_at so we can track changes
        }); // close the table definition callback
    } // close the up method to finish the build step
    public function down(): void { // define the rollback step that removes this table
        Schema::dropIfExists('cars'); // drop the table if it exists to reverse the migration
    } // close the down method to finish the rollback step
}; // close the anonymous class returned to Laravel
