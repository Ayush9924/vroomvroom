<?php // allow PHP code in this model file so Laravel can load the class
namespace App\Models; // declare this class lives in the Models namespace for autoloading
use Illuminate\Database\Eloquent\Model; // import the base Model class so we get Eloquent features
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory so factory() is available
use App\Models\Location; // import the Location model so we can reference it in relationships
use App\Models\Booking; // import the Booking model so we can reference it in relationships
class Car extends Model // define the Car model that maps to the cars table
{ // open the class body so we can add configuration
    use HasFactory; // enable factory() on this model so seeders can create fake data
    protected $fillable = [ // list fields that are safe to mass-assign from user input
        'category_id',
        'type', // allow the type column to be filled when creating or updating
        'name',
        'car_name',
        'brand',
        'year',
        'color',
        'seats',
        'transmission',
        'fuel_type',
        'mileage',
        'image',
        'location_id', // allow the location foreign key to be filled when creating or updating
        'price_per_day', // allow the price column to be filled when creating or updating
        'is_available', // allow the availability flag to be filled when creating or updating
    ]; // close the fillable array so Laravel knows the allowed fields
    protected $guarded = [ // list fields that should never be mass-assigned for safety
        'id', // protect the primary key so users cannot overwrite it
    ]; // close the guarded array to finish the protection list
    public function location() // define a relationship method so a car can access its location
    { // open the method body so we can return the relationship
        return $this->belongsTo(Location::class); // say each car belongs to one location
    } // close the method body to finish the relationship

    public function category()
    {
        return $this->belongsTo(CarCategory::class, 'category_id');
    }
    public function bookings() // define a relationship method so a car can list its bookings
    { // open the method body so we can return the relationship
        return $this->hasMany(Booking::class); // say one car has many bookings using the Booking model
    } // close the method body to finish the relationship

    // NEW CONCEPT: A helper method to calculate dynamic data!
    // NEW CONCEPT: Enhancing Business Logic!
    public function getCurrentStatus()
    {
        $today = now()->toDateString(); 

        // 1. Is it rented out exactly TODAY?
        $rentedToday = $this->bookings()
            ->where('status', '!=', 'cancelled') // IGNORE CANCELLED BOOKINGS!
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();

        if ($rentedToday) {
            return 'Rented Out Right Now';
        }

        // 2. Does it have any FUTURE bookings?
        $bookedFuture = $this->bookings()
            ->where('status', '!=', 'cancelled') // IGNORE CANCELLED BOOKINGS!
            ->where('start_date', '>', $today)
            ->exists();

        if ($bookedFuture) {
            return 'Booked for Future';
        }

        // 3. Otherwise, it's completely free!
        return 'Sitting in Garage';
    }
} // close the class body to finish the model
