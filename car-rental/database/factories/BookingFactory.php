<?php // allow PHP code in this factory file so Laravel can load it
namespace Database\Factories; // declare this class lives in the database factories namespace
use App\Models\Booking; // import the Booking model so the factory knows which model it builds
use App\Models\Car; // import the Car model so we can link bookings to cars
use App\Models\User; // import the User model so we can link bookings to users
use Illuminate\Database\Eloquent\Factories\Factory; // import the base Factory class so we can extend it
class BookingFactory extends Factory // define a factory class that creates fake Booking data
{ // open the class body so we can add configuration and methods
    protected $model = Booking::class; // link this factory to the Booking model explicitly
    public function definition(): array // define the default fake data for a Booking
    { // open the method body so we can return the fake attributes
        $start = $this->faker->dateTimeBetween('-1 week', '+2 weeks'); // pick a start date near today for realistic bookings
        $end = (clone $start)->modify('+'.rand(1, 7).' days'); // pick an end date a few days after the start date
        return [ // return an array of fake column values for a booking
            'user_id' => User::factory(), // create or reuse a related user and link the booking to them
            'car_id' => Car::factory(), // create or reuse a related car and link the booking to it
            'start_date' => $start->format('Y-m-d'), // store the start date in the database date format
            'end_date' => $end->format('Y-m-d'), // store the end date in the database date format
            'total_price' => $this->faker->randomFloat(2, 100, 1500), // generate a realistic total price for the booking
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']), // choose a realistic status value
        ]; // close the attributes array to finish the fake data definition
    } // close the method body to finish the definition
} // close the class body to finish the factory
