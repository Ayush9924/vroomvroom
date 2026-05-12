<?php // allow PHP code in this migration file so Laravel can execute it
use Illuminate\Database\Migrations\Migration; // import Migration so we can define the up/down steps
use Illuminate\Database\Schema\Blueprint; // import Blueprint so we can describe table columns
use Illuminate\Support\Facades\Schema; // import Schema so we can create and drop tables
return new class extends Migration { // create an anonymous migration class for this table
    public function up(): void { // define the build step that creates the bookings table
        Schema::create('bookings', function (Blueprint $table) { // start defining the bookings table
            $table->id(); // add an auto-incrementing primary key so each booking is unique
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // link each booking to a user and delete bookings if the user is deleted
            $table->foreignId('car_id')->constrained()->cascadeOnDelete(); // link each booking to a car and delete bookings if the car is deleted
            $table->date('start_date'); // store the rental start date so availability can be checked
            $table->date('end_date'); // store the rental end date so availability can be checked
            $table->decimal('total_price', 10, 2); // store the final price for this booking
            $table->string('status')->default('pending'); // store the booking status like pending or confirmed
            $table->timestamps(); // add created_at and updated_at so we can track changes
        }); // close the table definition callback
    } // close the up method to finish the build step
    public function down(): void { // define the rollback step that removes this table
        Schema::dropIfExists('bookings'); // drop the table if it exists to reverse the migration
    } // close the down method to finish the rollback step
}; // close the anonymous class returned to Laravel
