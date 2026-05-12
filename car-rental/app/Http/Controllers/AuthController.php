<?php // allow PHP code in this controller file so Laravel can load the class
namespace App\Http\Controllers; // declare this class lives in the Controllers namespace for autoloading
use Illuminate\Http\Request; // import the Request class so we can read form input later
use App\Models\User; // import the User model so we can create new users
use Illuminate\Support\Facades\Hash; // import Hash so we can securely hash passwords
class AuthController extends Controller // define a controller class to handle authentication actions
{ // open the class body so we can add methods inside it
    public function showRegister() // create a method that displays the registration form
    { // open the method body so we can return a response
        return view('auth.register'); // return the register Blade view so the user can sign up
    } // close the method body to finish the showRegister action
    public function storeRegister(Request $request) // create a method that handles the form submission
    { // open the method body so we can return a response
        $validated = $request->validate([ // validate the incoming form data and store the clean result
            'name' => 'required|string|min:3|max:100', // require a name with a reasonable length
            'email' => 'required|email|unique:users,email', // require a unique, valid email address
            'password' => 'required|string|min:8|confirmed', // require a strong password and matching confirmation
        ]); // close the validation rules array
        $user = User::create([ // create a new user record in the database
            'name' => $validated['name'], // set the name from the validated input
            'email' => $validated['email'], // set the email from the validated input
            'password' => Hash::make($validated['password']), // hash the password so it is not stored as plain text
        ]); // close the user creation array
        return redirect('/')->with('success', 'Account created'); // send the user to the home page with a success message
    } // close the method body to finish the storeRegister action
} // close the class body to finish the controller definition
