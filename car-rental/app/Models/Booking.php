<?php // allow PHP code in this model file so Laravel can load the class
namespace App\Models; // declare this class lives in the Models namespace for autoloading
use Illuminate\Database\Eloquent\Model; // import the base Model class so we get Eloquent features
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory so factory() is available
use App\Models\User; // import the User model so we can reference it in relationships
use App\Models\Car; // import the Car model so we can reference it in relationships
class Booking extends Model // define the Booking model that maps to the bookings table
{ // open the class body so we can add configuration
    use HasFactory; // enable factory() on this model so seeders can create fake data
    protected $fillable = [ // list fields that are safe to mass-assign from user input
        'user_id', // allow the user foreign key to be filled when creating or updating
        'car_id', // allow the car foreign key to be filled when creating or updating
        'start_date', // allow the start date to be filled when creating or updating
        'end_date', // allow the end date to be filled when creating or updating
        'total_price', // allow the total price to be filled when creating or updating
        'status', // allow the status to be filled when creating or updating
        'razorpay_order_id', // allow razorpay order id to be saved
        'razorpay_payment_id', // allow razorpay payment id to be saved after payment
        'razorpay_signature', // allow razorpay signature to be saved for verification
        'paid_at', // allow the payment timestamp to be recorded
    ]; // close the fillable array so Laravel knows the allowed fields
    protected $guarded = [ // list fields that should never be mass-assigned for safety
        'id', // protect the primary key so users cannot overwrite it
    ]; // close the guarded array to finish the protection list
    public function user() // define a relationship method so a booking can access its user
    { // open the method body so we can return the relationship
        return $this->belongsTo(User::class); // say each booking belongs to one user
    } // close the method body to finish the relationship
    public function car() // define a relationship method so a booking can access its car
    { // open the method body so we can return the relationship
        return $this->belongsTo(Car::class); // say each booking belongs to one car
    } // close the method body to finish the relationship
} // close the class body to finish the model
