<?php // allow PHP code in this factory file so Laravel can load it
namespace Database\Factories; // declare this class lives in the database factories namespace
use App\Models\Car; // import the Car model so the factory knows which model it builds
use App\Models\Location; // import the Location model so we can link cars to locations
use Illuminate\Database\Eloquent\Factories\Factory; // import the base Factory class so we can extend it
class CarFactory extends Factory // define a factory class that creates fake Car data
{ // open the class body so we can add configuration and methods
    protected $model = Car::class; // link this factory to the Car model explicitly
    public function definition(): array // define the default fake data for a Car
    { // open the method body so we can return the fake attributes
        return [ // return an array of fake column values for a car
            'type' => $this->faker->randomElement(['Sedan', 'SUV', 'Hatchback', 'Truck']), // pick a realistic car type for filtering tests
            'location_id' => Location::factory(), // create or reuse a related location and link the car to it
            'price_per_day' => $this->faker->randomFloat(2, 25, 250), // generate a daily price between 25 and 250
            'is_available' => $this->faker->boolean(80), // make most cars available to mimic real inventory
        ]; // close the attributes array to finish the fake data definition
    } // close the method body to finish the definition
} // close the class body to finish the factory
