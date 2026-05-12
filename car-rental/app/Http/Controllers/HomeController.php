<?php // allow PHP code in this controller file so Laravel can load the class
namespace App\Http\Controllers; // declare this class lives in the Controllers namespace so Laravel can autoload it
use Illuminate\Http\Request; // import the Request class now so we can use it later without long names
use App\Models\Car; // import the Car model to fetch cars for the landing page

class HomeController extends Controller // define a controller class that can handle web requests
{ // open the class body so we can add methods inside it
    public function index() // create an action method for the home page so a route can call it
    { // open the method body so we can write the response logic
        // Fetch all cars to display in the featured section
        $cars = Car::all(); 

        return view('welcome', [ // return the new beautiful welcome Blade view
            'cars' => $cars, // pass the cars to the view
        ]); // close the view call and send the response back to the browser
    } // close the method body to finish the index action
} // close the class body to finish the controller definition
