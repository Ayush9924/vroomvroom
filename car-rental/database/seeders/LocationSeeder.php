<?php // allow PHP code in this seeder file so Laravel can run it
namespace Database\Seeders; // declare this class lives in the seeders namespace for autoloading
use Illuminate\Database\Seeder; // import the base Seeder class so we can extend it
use App\Models\Location; // import the Location model so we can insert location records
class LocationSeeder extends Seeder // define a seeder class that inserts location data
{ // open the class body so we can add the run method
    public function run(): void // define the method Laravel calls when this seeder runs
    { // open the method body so we can add seeding instructions
        Location::factory()->count(5)->create(); // create 5 fake locations using the factory for testing
    } // close the method body to finish the seeding instructions
} // close the class body to finish the seeder
