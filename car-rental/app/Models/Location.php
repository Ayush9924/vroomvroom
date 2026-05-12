<?php // allow PHP code in this model file so Laravel can load the class
namespace App\Models; // declare this class lives in the Models namespace for autoloading
use Illuminate\Database\Eloquent\Model; // import the base Model class so we get Eloquent features
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory so factory() is available
class Location extends Model // define the Location model that maps to the locations table
{ // open the class body so we can add configuration
    use HasFactory; // enable factory() on this model so seeders can create fake data
    protected $fillable = [ // list fields that are safe to mass-assign from user input
        'name', // allow the name column to be filled when creating or updating
        'city', // allow the city column to be filled when creating or updating
        'address', // allow the address column to be filled when creating or updating
    ]; // close the fillable array so Laravel knows the allowed fields
    protected $guarded = [ // list fields that should never be mass-assigned for safety
        'id', // protect the primary key so users cannot overwrite it
    ]; // close the guarded array to finish the protection list
    public function cars() // define a relationship method so a location can list its cars
    { // open the method body so we can return the relationship
        return $this->hasMany(Car::class); // say one location has many cars using the Car model
    } // close the method body to finish the relationship
} // close the class body to finish the model
