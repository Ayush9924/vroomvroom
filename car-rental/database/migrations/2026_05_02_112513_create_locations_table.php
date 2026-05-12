<?php // allow PHP code in this migration file so Laravel can execute it
use Illuminate\Database\Migrations\Migration; // import Migration so we can define the up/down steps
use Illuminate\Database\Schema\Blueprint; // import Blueprint so we can describe table columns
use Illuminate\Support\Facades\Schema; // import Schema so we can create and drop tables
return new class extends Migration { // create an anonymous migration class for this table
    public function up(): void { // define the build step that creates the locations table
        Schema::create('locations', function (Blueprint $table) { // start defining the locations table
            $table->id(); // add an auto-incrementing primary key so each location is unique
            $table->string('name'); // store a human-friendly location name for display
            $table->string('city'); // store the city so users can filter by area
            $table->string('address'); // store the full address for pickup details
            $table->timestamps(); // add created_at and updated_at so we can track changes
        }); // close the table definition callback
    } // close the up method to finish the build step
    public function down(): void { // define the rollback step that removes this table
        Schema::dropIfExists('locations'); // drop the table if it exists to reverse the migration
    } // close the down method to finish the rollback step
}; // close the anonymous class returned to Laravel
