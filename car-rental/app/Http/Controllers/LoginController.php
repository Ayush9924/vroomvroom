<?php // allow PHP code in this controller file so Laravel can load the class
namespace App\Http\Controllers; // declare this class lives in the Controllers namespace for autoloading
use Illuminate\Http\Request; // import the Request class so we can read form input later
use Illuminate\Support\Facades\Auth; // import Auth so we can attempt login with credentials
class LoginController extends Controller // define a controller class to handle login actions
{ // open the class body so we can add methods inside it
    public function showLogin() // create a method that displays the login form
    { // open the method body so we can return a response
        return view('auth.login'); // return the login Blade view so the user can sign in
    } // close the method body to finish the showLogin action
    public function storeLogin(Request $request) // create a method that handles the login form submission
    { // open the method body so we can return a response
        $validated = $request->validate([ // validate the incoming login data and store the clean result
            'email' => 'required|email', // require a valid email so we know who is logging in
            'password' => 'required|string|min:8', // require a password with a minimum length
        ]); // close the validation rules array
        if (Auth::attempt($validated)) { // try to log the user in with the validated credentials
            $request->session()->regenerate(); // regenerate the session ID to prevent session fixation
            return redirect('/')->with('success', 'Logged in successfully'); // send the user to home with a success message
        } // close the success branch if login fails
        return back()->withErrors([ // send back an error if the credentials are wrong
            'email' => 'The provided credentials do not match our records.', // show a friendly error message
        ])->onlyInput('email'); // keep the email input so the user does not retype it
    } // close the method body to finish the storeLogin action
    public function logout(Request $request) // create a method that logs the user out safely
    { // open the method body so we can return a response
        Auth::logout(); // log out the current user to clear authentication
        $request->session()->invalidate(); // invalidate the session to remove old session data
        $request->session()->regenerateToken(); // regenerate the CSRF token to protect future requests
        return redirect('/')->with('success', 'Logged out successfully'); // send the user to home with a logout message
    } // close the method body to finish the logout action
} // close the class body to finish the controller definition
