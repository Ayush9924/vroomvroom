<?php // allow PHP code in this seeder file so Laravel can run it
namespace Database\Seeders; // declare this class lives in the seeders namespace for autoloading
use Illuminate\Database\Seeder; // import the base Seeder class so we can extend it
use App\Models\Car; // import the Car model so we can insert car records
class CarSeeder extends Seeder // define a seeder class that inserts car data
{ // open the class body so we can add the run method
    public function run(): void // define the method Laravel calls when this seeder runs
    { // open the method body so we can add seeding instructions
        Car::factory()->count(20)->create(); // create 20 fake cars using the factory for testing
    } // close the method body to finish the seeding instructions
} // close the class body to finish the seeder
