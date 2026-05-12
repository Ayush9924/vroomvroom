<?php // allow PHP code in this seeder file so Laravel can run it
namespace Database\Seeders; // declare this class lives in the seeders namespace for autoloading
use Illuminate\Database\Seeder; // import the base Seeder class so we can extend it
use App\Models\Booking; // import the Booking model so we can insert booking records
class BookingSeeder extends Seeder // define a seeder class that inserts booking data
{ // open the class body so we can add the run method
    public function run(): void // define the method Laravel calls when this seeder runs
    { // open the method body so we can add seeding instructions
        Booking::factory()->count(30)->create(); // create 30 fake bookings using the factory for testing
    } // close the method body to finish the seeding instructions
} // close the class body to finish the seeder
