<?php // allow PHP code in this factory file so Laravel can load it
namespace Database\Factories; // declare this class lives in the database factories namespace
use App\Models\Location; // import the Location model so the factory knows which model it builds
use Illuminate\Database\Eloquent\Factories\Factory; // import the base Factory class so we can extend it
class LocationFactory extends Factory // define a factory class that creates fake Location data
{ // open the class body so we can add configuration and methods
    protected $model = Location::class; // link this factory to the Location model explicitly
    public function definition(): array // define the default fake data for a Location
    { // open the method body so we can return the fake attributes
        return [ // return an array of fake column values for a location
            'name' => $this->faker->company() . ' Branch', // create a realistic location name with a business name
            'city' => $this->faker->city(), // create a city name so we can filter locations by city
            'address' => $this->faker->streetAddress(), // create a street address for pickup details
        ]; // close the attributes array to finish the fake data definition
    } // close the method body to finish the definition
} // close the class body to finish the factory
