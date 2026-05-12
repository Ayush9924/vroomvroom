<?php // allow PHP code in this seeder file so Laravel can run it
namespace Database\Seeders; // declare this class lives in the seeders namespace for autoloading
use App\Models\User; // import the User model so we can seed a test user
use Illuminate\Database\Console\Seeds\WithoutModelEvents; // import helper to disable model events during seeding
use Illuminate\Database\Seeder; // import the base Seeder class so we can extend it
class DatabaseSeeder extends Seeder // define the main seeder that calls other seeders
{ // open the class body so we can add the run method
    use WithoutModelEvents; // disable model events during seeding to keep it simple
    public function run(): void // define the method Laravel calls when seeding
    { // open the method body so we can call other seeders
        $this->call([ // call a list of seeders so they run in order
            LocationSeeder::class, // run the LocationSeeder to insert fake locations
            CarCategorySeeder::class,
            CarSeeder::class, // run the CarSeeder to insert fake cars linked to locations
            BookingSeeder::class, // run the BookingSeeder to insert fake bookings linked to users and cars
        ]); // close the call list so Laravel can execute it
        User::factory()->create([ // create a single test user for login testing
            'name' => 'Test User', // set the test user's name so we can recognize it
            'email' => 'test@example.com', // set a known email so we can sign in easily
        ]); // close the user creation call
    } // close the run method to finish the seeding instructions
} // close the class body to finish the seeder
