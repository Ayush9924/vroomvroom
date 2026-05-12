# Car Rental Platform (Laravel) - Full Walkthrough

This README explains every custom part of the project from start to end, using the same teaching style.

## 0) Run the project locally

**Concept**
You need the local server and database running so Laravel can respond to requests.

**Why it matters**
If the app is not running, you cannot test routes, forms, or bookings.

**Code with comments**
```bash
composer install # install PHP dependencies so Laravel can run
cp .env.example .env # create a local environment file so Laravel can read config
php artisan key:generate # create the app key so sessions and encryption work
php artisan migrate # build all database tables from migrations
php artisan db:seed # insert fake data so the app has test content
php artisan serve # start the local development server
```

**Your task**
Run the commands above and open http://127.0.0.1:8000.

## 1) Project map (folder structure)

**Concept**
Laravel keeps each responsibility in a predictable folder.

**Why it matters**
Knowing where code lives makes you faster and prevents messy code.

**Code with comments**
```
app/Http/Controllers   # controllers that handle web requests
app/Models             # Eloquent models that map to tables
app/Http/Middleware    # middleware that protects routes
database/migrations    # schema files that build tables
database/factories     # fake data definitions for testing
database/seeders       # scripts that insert fake data
resources/views        # Blade templates (HTML views)
routes/web.php         # web routes for browser traffic
```

**Your task**
Name one file where routes are defined and one file where views are stored.

## 2) Routes (routes/web.php)

**Concept**
Routes map URLs to controller methods or closures. Think of them like a switchboard.

**Why it matters**
If a route is missing, the page does not exist and returns 404.

**Code with comments**
```php
<?php // allow PHP code in the routes file so Laravel can read it
use Illuminate\Support\Facades\Route; // import Route so we can define URLs
use App\Http\Controllers\HomeController; // import HomeController for the home page
use App\Http\Controllers\AuthController; // import AuthController for registration
use App\Http\Controllers\LoginController; // import LoginController for login and logout
use App\Http\Controllers\BookingController; // import BookingController for booking flow
use App\Http\Controllers\AdminController; // import AdminController for admin panel
Route::get('/', [HomeController::class, 'index']); // map GET / to the home controller method
Route::get('/register', [AuthController::class, 'showRegister']); // show the registration form
Route::post('/register', [AuthController::class, 'storeRegister']); // handle registration submit
Route::get('/login', [LoginController::class, 'showLogin'])->name('login'); // show login form and name it
Route::post('/login', [LoginController::class, 'storeLogin']); // handle login submit
Route::post('/logout', [LoginController::class, 'logout']); // handle logout safely
Route::get('/dashboard', function () { // define the dashboard route for logged-in users
	$bookings = auth()->user()->bookings()->with('car')->get(); // load user bookings with car info
	return view('dashboard', ['bookings' => $bookings]); // return the dashboard view with data
})->middleware('auth'); // protect the dashboard with auth middleware
Route::get('/bookings/create', [BookingController::class, 'showCreate'])->middleware('auth'); // step 1: choose dates
Route::post('/bookings/dates', [BookingController::class, 'storeDates'])->middleware('auth'); // save dates in session
Route::get('/bookings/cars', [BookingController::class, 'showCars'])->middleware('auth'); // step 2: show available cars
Route::post('/bookings', [BookingController::class, 'store'])->middleware('auth'); // create a booking
Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])->middleware('auth'); // fake payment action
Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->middleware('auth'); // cancel booking
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () { // group admin routes and protect them
	Route::get('/', [AdminController::class, 'index']); // show admin dashboard
	Route::post('/cars', [AdminController::class, 'storeCar']); // add a new car
	Route::get('/cars/{car}/edit', [AdminController::class, 'editCar']); // show edit form
	Route::put('/cars/{car}', [AdminController::class, 'updateCar']); // update a car
	Route::delete('/cars/{car}', [AdminController::class, 'destroyCar']); // delete a car
}); // close the admin route group
```

**Your task**
Explain in one sentence what a route does.

## 3) Layout and navigation (resources/views/layouts/app.blade.php)

**Concept**
The layout is a shared page shell. Think of it like a reusable frame for every page.

**Why it matters**
It keeps navigation and styles consistent across the entire app.

**Code with comments**
```blade
<!doctype html> {{-- declare HTML5 so the browser renders correctly --}}
<html lang="en"> {{-- set the language for accessibility --}}
<head> {{-- open the head section --}}
	<meta charset="utf-8"> {{-- set UTF-8 for correct text --}}
	<meta name="viewport" content="width=device-width, initial-scale=1"> {{-- enable responsive layout --}}
	<title>{{ $pageTitle ?? 'Car Rental' }}</title> {{-- show page title or a default --}}
	<style> {{-- open the style block --}}
		body { font-family: Arial, sans-serif; margin: 2rem; } /* set base font and spacing */
		.tagline { color: #555; } /* make the tagline softer */
	</style> {{-- close the style block --}}
</head> {{-- close the head section --}}
<body> {{-- open the body section --}}
	<div> {{-- wrap the top navigation area --}}
		@if (session('success')) {{-- check for a flash success message --}}
			<div style="color: green; font-weight: bold; margin-bottom: 1rem;"> {{-- style the message for visibility --}}
				{{ session('success') }} {{-- print the success message text --}}
			</div> {{-- close the message box --}}
		@endif {{-- end the success check --}}
		@auth {{-- show links for logged-in users --}}
			@if(auth()->user()->is_admin) {{-- check if the user is an admin --}}
				<a href="/admin" style="margin-right: 1rem; color: red;">Admin Panel</a> {{-- show the admin link --}}
			@endif {{-- end the admin check --}}
			<a href="/dashboard" style="margin-right: 1rem;">Dashboard</a> {{-- link to the dashboard --}}
			<a href="/bookings/create" style="margin-right: 1rem;">Book a Car</a> {{-- link to booking flow --}}
			<form method="POST" action="/logout" style="display: inline;"> {{-- logout form to send POST --}}
				@csrf {{-- include CSRF token for security --}}
				<button type="submit">Logout</button> {{-- logout button --}}
			</form> {{-- close the logout form --}}
		@endauth {{-- end the logged-in block --}}
		@guest {{-- show links for guests --}}
			<a href="/login">Login</a> {{-- link to login page --}}
			<a href="/register">Register</a> {{-- link to register page --}}
		@endguest {{-- end the guest block --}}
	</div> {{-- close the nav wrapper --}}
	@yield('content') {{-- render the page-specific content --}}
</body> {{-- close the body section --}}
</html> {{-- close the HTML document --}}
```

**Your task**
Explain why layouts are useful in one sentence.

## 4) Home page (controller + view)

**Concept**
The home page is a simple controller returning a view with data.

**Why it matters**
This pattern is reused for every page in the app.

**Code with comments**
```php
public function index() // define the method that handles the home page
{ // open the method body to build the response
	$pageTitle = 'Car Rental Home'; // set the title for the page
	$tagline = 'Move the way you like, book and Drive'; // set a short tagline for the page
	return view('home', [ // return the home view with data
		'pageTitle' => $pageTitle, // pass the title to the view
		'tagline' => $tagline, // pass the tagline to the view
	]); // finish the response
} // close the method
```

```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start the page content section --}}
	@if (session('success')) {{-- show a success message if it exists --}}
		<p>{{ session('success') }}</p> {{-- print the message text --}}
	@endif {{-- end the success check --}}
	<h1>{{ $pageTitle }}</h1> {{-- display the page title --}}
	<p class="tagline">{{ $tagline }}</p> {{-- display the tagline --}}
@endsection {{-- end the page content section --}}
```

**Your task**
Explain in one sentence how the controller sends data to the view.

## 5) Database migrations (schema)

**Concept**
Migrations define your database tables. Think of them like blueprints.

**Why it matters**
They keep your database consistent and versioned.

**Code with comments**
```php
Schema::create('users', function (Blueprint $table) { // define the users table
	$table->id(); // primary key for users
	$table->string('name'); // store the user's name
	$table->string('email')->unique(); // store email and enforce uniqueness
	$table->timestamp('email_verified_at')->nullable(); // optional email verification time
	$table->string('password'); // hashed password storage
	$table->rememberToken(); // token for remember-me sessions
	$table->timestamps(); // created_at and updated_at
}); // close users table
Schema::create('password_reset_tokens', function (Blueprint $table) { // define password reset tokens
	$table->string('email')->primary(); // email is the primary key
	$table->string('token'); // reset token value
	$table->timestamp('created_at')->nullable(); // token creation time
}); // close password reset tokens table
Schema::create('sessions', function (Blueprint $table) { // define sessions table for database sessions
	$table->string('id')->primary(); // session id is primary key
	$table->foreignId('user_id')->nullable()->index(); // optional user id link
	$table->string('ip_address', 45)->nullable(); // store ip address
	$table->text('user_agent')->nullable(); // store browser info
	$table->longText('payload'); // session data payload
	$table->integer('last_activity')->index(); // session last activity time
}); // close sessions table
```

```php
Schema::table('users', function (Blueprint $table) { // modify the users table
	$table->boolean('is_admin')->default(false); // add admin flag, default false
}); // close the table modification
```

```php
Schema::create('locations', function (Blueprint $table) { // define locations table
	$table->id(); // primary key for locations
	$table->string('name'); // location name
	$table->string('city'); // location city
	$table->string('address'); // pickup address
	$table->timestamps(); // created_at and updated_at
}); // close locations table
```

```php
Schema::create('cars', function (Blueprint $table) { // define cars table
	$table->id(); // primary key for cars
	$table->string('type'); // car type label
	$table->foreignId('location_id')->constrained()->cascadeOnDelete(); // link to locations
	$table->decimal('price_per_day', 8, 2); // price per day
	$table->boolean('is_available')->default(true); // manual availability flag
	$table->timestamps(); // created_at and updated_at
}); // close cars table
```

```php
Schema::create('bookings', function (Blueprint $table) { // define bookings table
	$table->id(); // primary key for bookings
	$table->foreignId('user_id')->constrained()->cascadeOnDelete(); // link to users
	$table->foreignId('car_id')->constrained()->cascadeOnDelete(); // link to cars
	$table->date('start_date'); // booking start date
	$table->date('end_date'); // booking end date
	$table->decimal('total_price', 10, 2); // total price
	$table->string('status')->default('pending'); // booking status
	$table->timestamps(); // created_at and updated_at
}); // close bookings table
```

**Your task**
Name the foreign keys in the bookings table.

## 6) Models and relationships

**Concept**
Models map to tables and relationships connect them. Think of it like a family tree.

**Why it matters**
It keeps database access clean and readable.

**Code with comments**
```php
use Database\Factories\UserFactory; // import factory type for the model
use Illuminate\Database\Eloquent\Attributes\Fillable; // import Fillable attribute
use Illuminate\Database\Eloquent\Attributes\Hidden; // import Hidden attribute
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory for factories
use Illuminate\Foundation\Auth\User as Authenticatable; // import base auth user
use Illuminate\Notifications\Notifiable; // import notifications trait
use App\Models\Booking; // import Booking so we can define relationship
#[Fillable(['name', 'email', 'password'])] // allow mass assignment for name, email, password
#[Hidden(['password', 'remember_token'])] // hide sensitive fields when serializing
class User extends Authenticatable // define the User model
{ // open the class body
	use HasFactory, Notifiable; // enable factories and notifications
	protected function casts(): array // define attribute casts
	{ // open the method body
		return [ // return the cast map
			'email_verified_at' => 'datetime', // cast email_verified_at to datetime
			'password' => 'hashed', // auto-hash passwords when set
		]; // close the cast map
	} // close the method
	public function bookings() // define a relationship to bookings
	{ // open the method body
		return $this->hasMany(Booking::class); // one user has many bookings
	} // close the method
} // close the class
```

```php
use Illuminate\Database\Eloquent\Model; // import base model
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory
class Location extends Model // define Location model
{ // open class body
	use HasFactory; // enable factories
	protected $fillable = ['name', 'city', 'address']; // allow these fields to be mass-assigned
	protected $guarded = ['id']; // protect the primary key
	public function cars() // define relationship to cars
	{ // open method
		return $this->hasMany(Car::class); // one location has many cars
	} // close method
} // close class
```

```php
use Illuminate\Database\Eloquent\Model; // import base model
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory
use App\Models\Location; // import Location model
use App\Models\Booking; // import Booking model
class Car extends Model // define Car model
{ // open class body
	use HasFactory; // enable factories
	protected $fillable = ['type', 'location_id', 'price_per_day', 'is_available']; // allow these fields
	protected $guarded = ['id']; // protect the primary key
	public function location() // define relationship to location
	{ // open method
		return $this->belongsTo(Location::class); // each car belongs to a location
	} // close method
	public function bookings() // define relationship to bookings
	{ // open method
		return $this->hasMany(Booking::class); // one car has many bookings
	} // close method
	public function getCurrentStatus() // calculate a dynamic status string
	{ // open method
		$today = now()->toDateString(); // get today's date
		$rentedToday = $this->bookings() // start booking query
			->where('status', '!=', 'cancelled') // ignore cancelled bookings
			->where('start_date', '<=', $today) // booking starts before or on today
			->where('end_date', '>=', $today) // booking ends after or on today
			->exists(); // check if any matching booking exists
		if ($rentedToday) { // check if rented today
			return 'Rented Out Right Now'; // return the current status
		} // end rented check
		$bookedFuture = $this->bookings() // start another booking query
			->where('status', '!=', 'cancelled') // ignore cancelled bookings
			->where('start_date', '>', $today) // booking starts in the future
			->exists(); // check if any future booking exists
		if ($bookedFuture) { // check if booked in future
			return 'Booked for Future'; // return the future status
		} // end future check
		return 'Sitting in Garage'; // return default status when free
	} // close method
} // close class
```

```php
use Illuminate\Database\Eloquent\Model; // import base model
use Illuminate\Database\Eloquent\Factories\HasFactory; // import HasFactory
use App\Models\User; // import User model
use App\Models\Car; // import Car model
class Booking extends Model // define Booking model
{ // open class body
	use HasFactory; // enable factories
	protected $fillable = ['user_id', 'car_id', 'start_date', 'end_date', 'total_price', 'status']; // allow these fields
	protected $guarded = ['id']; // protect the primary key
	public function user() // define relationship to user
	{ // open method
		return $this->belongsTo(User::class); // each booking belongs to a user
	} // close method
	public function car() // define relationship to car
	{ // open method
		return $this->belongsTo(Car::class); // each booking belongs to a car
	} // close method
} // close class
```

**Your task**
Explain what $car->bookings returns in one sentence.

## 7) Factories (fake data)

**Concept**
Factories generate fake records for testing.

**Why it matters**
They let you test without manual data entry.

**Code with comments**
```php
class LocationFactory extends Factory // define a factory for locations
{ // open class body
	protected $model = Location::class; // connect this factory to the Location model
	public function definition(): array // define fake data
	{ // open method
		return [ // return fake attributes
			'name' => $this->faker->company() . ' Branch', // fake location name
			'city' => $this->faker->city(), // fake city
			'address' => $this->faker->streetAddress(), // fake address
		]; // close attributes
	} // close method
} // close class
```

```php
class CarFactory extends Factory // define a factory for cars
{ // open class body
	protected $model = Car::class; // connect factory to Car
	public function definition(): array // define fake data
	{ // open method
		return [ // return fake attributes
			'type' => $this->faker->randomElement(['Sedan', 'SUV', 'Hatchback', 'Truck']), // fake type
			'location_id' => Location::factory(), // create a linked location
			'price_per_day' => $this->faker->randomFloat(2, 25, 250), // fake price
			'is_available' => $this->faker->boolean(80), // mostly available
		]; // close attributes
	} // close method
} // close class
```

```php
class BookingFactory extends Factory // define a factory for bookings
{ // open class body
	protected $model = Booking::class; // connect factory to Booking
	public function definition(): array // define fake data
	{ // open method
		$start = $this->faker->dateTimeBetween('-1 week', '+2 weeks'); // fake start date
		$end = (clone $start)->modify('+'.rand(1, 7).' days'); // fake end date
		return [ // return fake attributes
			'user_id' => User::factory(), // create a linked user
			'car_id' => Car::factory(), // create a linked car
			'start_date' => $start->format('Y-m-d'), // store start date
			'end_date' => $end->format('Y-m-d'), // store end date
			'total_price' => $this->faker->randomFloat(2, 100, 1500), // fake price
			'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']), // fake status
		]; // close attributes
	} // close method
} // close class
```

**Your task**
Explain why factories are useful.

## 8) Seeders (fake data insertion)

**Concept**
Seeders insert factory data into the database.

**Why it matters**
They populate the app quickly for testing.

**Code with comments**
```php
class LocationSeeder extends Seeder // define a seeder for locations
{ // open class body
	public function run(): void // define the seeding method
	{ // open method
		Location::factory()->count(5)->create(); // insert 5 locations
	} // close method
} // close class
```

```php
class CarSeeder extends Seeder // define a seeder for cars
{ // open class body
	public function run(): void // define the seeding method
	{ // open method
		Car::factory()->count(20)->create(); // insert 20 cars
	} // close method
} // close class
```

```php
class BookingSeeder extends Seeder // define a seeder for bookings
{ // open class body
	public function run(): void // define the seeding method
	{ // open method
		Booking::factory()->count(30)->create(); // insert 30 bookings
	} // close method
} // close class
```

```php
class DatabaseSeeder extends Seeder // define the master seeder
{ // open class body
	public function run(): void // define the run method
	{ // open method
		$this->call([ // call the seeders in order
			LocationSeeder::class, // seed locations first
			CarSeeder::class, // seed cars after locations
			BookingSeeder::class, // seed bookings after cars
		]); // close the seeder list
	} // close method
} // close class
```

**Your task**
Explain why seeders run after migrations.

## 9) Registration (AuthController + view)

**Concept**
Registration validates input, hashes the password, and creates a user.

**Why it matters**
It safely stores accounts for later login.

**Code with comments**
```php
public function showRegister() // show the registration form
{ // open method
	return view('auth.register'); // return the register view
} // close method
```

```php
public function storeRegister(Request $request) // handle registration submit
{ // open method
	$validated = $request->validate([ // validate input
		'name' => 'required|string|min:3|max:100', // require a valid name
		'email' => 'required|email|unique:users,email', // require a unique email
		'password' => 'required|string|min:8|confirmed', // require password and confirmation
	]); // close validation
	User::create([ // create a new user record
		'name' => $validated['name'], // store name
		'email' => $validated['email'], // store email
		'password' => Hash::make($validated['password']), // store hashed password
	]); // close creation
	return redirect('/')->with('success', 'Account created'); // send user home with message
} // close method
```

```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Register</h1> {{-- page title --}}
	@if ($errors->any()) {{-- show errors if validation failed --}}
		<div> {{-- wrap the error list --}}
			<p>Please fix the errors below:</p> {{-- instruction text --}}
			<ul> {{-- start error list --}}
				@foreach ($errors->all() as $error) {{-- loop through errors --}}
					<li>{{ $error }}</li> {{-- print one error --}}
				@endforeach {{-- end loop --}}
			</ul> {{-- close list --}}
		</div> {{-- close wrapper --}}
	@endif {{-- end error check --}}
	<form method="POST" action="/register"> {{-- submit to register route --}}
		@csrf {{-- include CSRF token --}}
		<div> {{-- name field wrapper --}}
			<label for="name">Name</label> {{-- name label --}}
			<input id="name" name="name" type="text" value="{{ old('name') }}" required> {{-- keep old name --}}
		</div> {{-- close name wrapper --}}
		<div> {{-- email field wrapper --}}
			<label for="email">Email</label> {{-- email label --}}
			<input id="email" name="email" type="email" value="{{ old('email') }}" required> {{-- keep old email --}}
		</div> {{-- close email wrapper --}}
		<div> {{-- password field wrapper --}}
			<label for="password">Password</label> {{-- password label --}}
			<input id="password" name="password" type="password" required> {{-- password input --}}
		</div> {{-- close password wrapper --}}
		<div> {{-- confirmation field wrapper --}}
			<label for="password_confirmation">Confirm Password</label> {{-- confirm label --}}
			<input id="password_confirmation" name="password_confirmation" type="password" required> {{-- confirm input --}}
		</div> {{-- close confirmation wrapper --}}
		<button type="submit">Create Account</button> {{-- submit button --}}
	</form> {{-- close form --}}
@endsection {{-- end content section --}}
```

**Your task**
Explain why passwords are hashed.

## 10) Login and logout (LoginController + view)

**Concept**
Login checks credentials and starts a session. Logout clears the session.

**Why it matters**
Sessions allow protected pages like bookings and admin.

**Code with comments**
```php
public function showLogin() // show the login form
{ // open method
	return view('auth.login'); // return the login view
} // close method
```

```php
public function storeLogin(Request $request) // handle login submit
{ // open method
	$validated = $request->validate([ // validate input
		'email' => 'required|email', // require a valid email
		'password' => 'required|string|min:8', // require a password
	]); // close validation
	if (Auth::attempt($validated)) { // attempt login with credentials
		$request->session()->regenerate(); // regenerate session id for security
		return redirect('/')->with('success', 'Logged in successfully'); // send user home
	} // end success check
	return back()->withErrors([ // return error on failure
		'email' => 'The provided credentials do not match our records.', // error message
	])->onlyInput('email'); // keep email input
} // close method
```

```php
public function logout(Request $request) // handle logout
{ // open method
	Auth::logout(); // clear authentication
	$request->session()->invalidate(); // invalidate session data
	$request->session()->regenerateToken(); // regenerate CSRF token
	return redirect('/')->with('success', 'Logged out successfully'); // send user home
} // close method
```

```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Login</h1> {{-- page title --}}
	@if ($errors->any()) {{-- show errors if validation failed --}}
		<div> {{-- wrap the error list --}}
			<p>Please fix the errors below:</p> {{-- instruction text --}}
			<ul> {{-- start error list --}}
				@foreach ($errors->all() as $error) {{-- loop through errors --}}
					<li>{{ $error }}</li> {{-- print one error --}}
				@endforeach {{-- end loop --}}
			</ul> {{-- close list --}}
		</div> {{-- close wrapper --}}
	@endif {{-- end error check --}}
	<form method="POST" action="/login"> {{-- submit to login route --}}
		@csrf {{-- include CSRF token --}}
		<div> {{-- email field wrapper --}}
			<label for="email">Email</label> {{-- email label --}}
			<input id="email" name="email" type="email" value="{{ old('email') }}" required> {{-- keep old email --}}
		</div> {{-- close email wrapper --}}
		<div> {{-- password field wrapper --}}
			<label for="password">Password</label> {{-- password label --}}
			<input id="password" name="password" type="password" required> {{-- password input --}}
		</div> {{-- close password wrapper --}}
		<button type="submit">Sign In</button> {{-- submit button --}}
	</form> {{-- close form --}}
@endsection {{-- end content section --}}
```

**Your task**
Explain what happens to the session after logout.

## 11) Dashboard (booking list + actions)

**Concept**
The dashboard shows the logged-in user's bookings and actions.

**Why it matters**
Users need a place to manage bookings, pay, or cancel.

**Code with comments**
```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Dashboard</h1> {{-- page title --}}
	<p>Only logged-in users can see this page.</p> {{-- access explanation --}}
	<h2>Your Bookings</h2> {{-- section heading --}}
	@if ($bookings->isEmpty()) {{-- check if there are any bookings --}}
		<p>No bookings yet.</p> {{-- show empty state --}}
	@else {{-- show list if bookings exist --}}
		<ul> {{-- open list --}}
			@foreach ($bookings as $booking) {{-- loop bookings --}}
				<li style="margin-bottom: 1rem;"> {{-- list item with spacing --}}
					<strong>{{ $booking->car->type }}</strong> (Car #{{ $booking->car->id }}) | {{-- show car info --}}
					Dates: {{ $booking->start_date }} to {{ $booking->end_date }} | {{-- show date range --}}
					Total: ${{ $booking->total_price }} | {{-- show total price --}}
					Status: {{-- status label --}}
					@if ($booking->status === 'cancelled') {{-- check cancelled status --}}
						<strong style="color: red;">Cancelled</strong> {{-- show cancelled badge --}}
					@else {{-- show other statuses --}}
						<strong>{{ ucfirst($booking->status) }}</strong> {{-- print status --}}
					@endif {{-- end status check --}}
					@if ($booking->status === 'pending') {{-- check if payment is needed --}}
						<form method="POST" action="/bookings/{{ $booking->id }}/pay" style="display: inline; margin-left: 10px;"> {{-- pay form --}}
							@csrf {{-- CSRF token --}}
							<button type="submit">Pay Now</button> {{-- pay button --}}
						</form> {{-- close pay form --}}
					@endif {{-- end pending check --}}
					@if ($booking->status !== 'cancelled') {{-- check if cancel is allowed --}}
						<form method="POST" action="/bookings/{{ $booking->id }}/cancel" style="display: inline; margin-left: 10px;"> {{-- cancel form --}}
							@csrf {{-- CSRF token --}}
							<button type="submit" style="color: red;" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button> {{-- cancel button --}}
						</form> {{-- close cancel form --}}
					@endif {{-- end cancel check --}}
				</li> {{-- close list item --}}
			@endforeach {{-- end loop --}}
		</ul> {{-- close list --}}
	@endif {{-- end empty check --}}
@endsection {{-- end content section --}}
```

**Your task**
Explain why we load bookings with car details.

## 12) Booking flow (two-step)

**Concept**
Booking happens in two steps: choose dates, then choose a car.

**Why it matters**
It ensures the car list is filtered by availability.

**Code with comments**
```php
public function showCreate() // show step 1: date form
{ // open method
	return view('bookings.create'); // return the date selection view
} // close method
```

```php
public function storeDates(Request $request) // save dates to session
{ // open method
	$validated = $request->validate([ // validate date inputs
		'start_date' => 'required|date|after_or_equal:today', // start date must be today or future
		'end_date' => 'required|date|after_or_equal:start_date', // end date must be after start date
	]); // close validation
	session(['booking_start_date' => $validated['start_date']]); // store start date in session
	session(['booking_end_date' => $validated['end_date']]); // store end date in session
	return redirect('/bookings/cars'); // go to step 2
} // close method
```

```php
public function showCars() // show step 2: available cars
{ // open method
	$start = session('booking_start_date'); // read start date from session
	$end = session('booking_end_date'); // read end date from session
	if (!$start || !$end) { // if dates are missing
		return redirect('/bookings/create'); // send back to step 1
	} // close missing check
	$availableCars = Car::where('is_available', true) // start with only available cars
		->whereDoesntHave('bookings', function ($query) use ($start, $end) { // exclude overlapping bookings
			$query->where('status', '!=', 'cancelled') // ignore cancelled bookings
				  ->where(function ($q) use ($start, $end) { // group overlap cases
					  $q->whereBetween('start_date', [$start, $end]) // overlap case 1
						->orWhereBetween('end_date', [$start, $end]) // overlap case 2
						->orWhere(function ($q2) use ($start, $end) { // overlap case 3
							$q2->where('start_date', '<=', $start) // starts before
							   ->where('end_date', '>=', $end); // ends after
						}); // close overlap case 3
				  }); // close overlap group
		})->get(); // finish query and get cars
	return view('bookings.cars', ['cars' => $availableCars, 'start' => $start, 'end' => $end]); // show cars
} // close method
```

```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Book a Car</h1> {{-- step 1 title --}}
	@if ($errors->any()) {{-- show errors if validation failed --}}
		<div> {{-- error wrapper --}}
			<p>Please fix the errors below:</p> {{-- instruction text --}}
			<ul> {{-- error list --}}
				@foreach ($errors->all() as $error) {{-- loop errors --}}
					<li>{{ $error }}</li> {{-- print error --}}
				@endforeach {{-- end loop --}}
			</ul> {{-- close list --}}
		</div> {{-- close wrapper --}}
	@endif {{-- end error check --}}
	<form method="POST" action="/bookings/dates"> {{-- submit dates --}}
		@csrf {{-- CSRF token --}}
		<div> {{-- start date wrapper --}}
			<label for="start_date">Start Date</label> {{-- label --}}
			<input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required> {{-- start date input --}}
		</div> {{-- close start wrapper --}}
		<div> {{-- end date wrapper --}}
			<label for="end_date">End Date</label> {{-- label --}}
			<input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" required> {{-- end date input --}}
		</div> {{-- close end wrapper --}}
		<button type="submit">Find Available Cars</button> {{-- submit dates --}}
	</form> {{-- close form --}}
@endsection {{-- end content section --}}
```

```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Step 2: Choose Your Car</h1> {{-- step 2 title --}}
	<div style="background: #eef; padding: 10px; margin-bottom: 20px;"> {{-- date summary box --}}
		<strong>Your Dates:</strong> {{ $start }} to {{ $end }} {{-- show chosen dates --}}
		<a href="/bookings/create" style="margin-left: 10px;">(Change Dates)</a> {{-- change dates link --}}
	</div> {{-- close date summary --}}
	@if ($errors->any()) {{-- show errors if validation failed --}}
		<div style="color: red; margin-bottom: 20px;"> {{-- error wrapper --}}
			<p>Please fix the errors below:</p> {{-- instruction text --}}
			<ul> {{-- error list --}}
				@foreach ($errors->all() as $error) {{-- loop errors --}}
					<li>{{ $error }}</li> {{-- print error --}}
				@endforeach {{-- end loop --}}
			</ul> {{-- close list --}}
		</div> {{-- close wrapper --}}
	@endif {{-- end error check --}}
	@if($cars->isEmpty()) {{-- check if no cars are available --}}
		<p style="color: red;">Sorry, no cars are available for those specific dates. Please try different dates.</p> {{-- empty message --}}
	@else {{-- show the available cars form --}}
		<form method="POST" action="/bookings"> {{-- submit the final booking --}}
			@csrf {{-- CSRF token --}}
			<div style="margin-bottom: 20px;"> {{-- car list wrapper --}}
				<label><strong>Available Cars:</strong></label><br><br> {{-- list label --}}
				@foreach($cars as $car) {{-- loop through cars --}}
					<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;"> {{-- car card --}}
						<label> {{-- make the whole card selectable --}}
							<input type="radio" name="car_id" value="{{ $car->id }}" required> {{-- select car id --}}
							{{ $car->type }} - ${{ $car->price_per_day }} / day {{-- show car details --}}
							<br> {{-- line break --}}
							<small>Located at: {{ $car->location->name ?? 'Main Garage' }}</small> {{-- show location name --}}
						</label> {{-- close label --}}
					</div> {{-- close car card --}}
				@endforeach {{-- end loop --}}
			</div> {{-- close list wrapper --}}
			<button type="submit" style="background: green; color: white; padding: 10px 20px;">Confirm Booking</button> {{-- submit booking --}}
		</form> {{-- close form --}}
	@endif {{-- end empty check --}}
@endsection {{-- end content section --}}
```

**Your task**
Explain why the booking flow is split into two steps.

## 13) Booking creation, payment, cancellation

**Concept**
We calculate price, create the booking, then allow payment or cancellation.

**Why it matters**
It simulates a real booking lifecycle.

**Code with comments**
```php
public function store(Request $request) // create the booking
{ // open method
	$validated = $request->validate([ // validate car selection
		'car_id' => 'required|integer|exists:cars,id', // require valid car id
	]); // close validation
	$startStr = session('booking_start_date'); // read start date from session
	$endStr = session('booking_end_date'); // read end date from session
	if (!$startStr || !$endStr) { return redirect('/bookings/create'); } // safety check
	$car = Car::findOrFail($validated['car_id']); // load the car
	if (!$car->is_available) { // check manual availability
		return back()->withErrors(['car_id' => 'This car is not available right now.']); // return error
	} // close availability check
	$overlapExists = Booking::where('car_id', $car->id) // check for overlap bookings
		->where('status', '!=', 'cancelled') // ignore cancelled bookings
		->where(function ($query) use ($startStr, $endStr) { // group overlap cases
			$query->whereBetween('start_date', [$startStr, $endStr]) // overlap case 1
				->orWhereBetween('end_date', [$startStr, $endStr]) // overlap case 2
				->orWhere(function ($query) use ($startStr, $endStr) { // overlap case 3
					$query->where('start_date', '<=', $startStr) // starts before
						->where('end_date', '>=', $endStr); // ends after
				}); // close overlap case 3
		})->exists(); // check if overlap exists
	if ($overlapExists) { // handle overlap
		return back()->withErrors(['car_id' => 'Someone just booked this car! Please pick another.']); // return error
	} // close overlap check
	$start = Carbon::parse($startStr); // parse start date
	$end = Carbon::parse($endStr); // parse end date
	$days = $start->diffInDays($end) + 1; // count days including start
	$totalPrice = $days * $car->price_per_day; // compute total price
	Booking::create([ // create booking record
		'user_id' => Auth::id(), // attach logged-in user
		'car_id' => $car->id, // attach selected car
		'start_date' => $startStr, // store start date
		'end_date' => $endStr, // store end date
		'total_price' => $totalPrice, // store total price
		'status' => 'pending', // default status
	]); // close creation
	session()->forget(['booking_start_date', 'booking_end_date']); // clear session dates
	return redirect('/dashboard')->with('success', 'Booking created! Please pay to confirm.'); // redirect
} // close method
```

```php
public function pay(Booking $booking) // fake payment for a booking
{ // open method
	if ($booking->user_id !== Auth::id()) { // ensure the booking belongs to user
		abort(403); // stop with forbidden error
	} // close ownership check
	$booking->update(['status' => 'paid']); // mark the booking as paid
	return back()->with('success', 'Payment successful! Your car is reserved.'); // return success
} // close method
```

```php
public function cancel(Booking $booking) // cancel a booking
{ // open method
	if ($booking->user_id !== Auth::id()) { // ensure user owns booking
		abort(403); // stop with forbidden error
	} // close ownership check
	if ($booking->status === 'cancelled') { // check if already cancelled
		return back()->with('error', 'This booking is already cancelled.'); // return error
	} // close cancelled check
	$wasPaid = $booking->status === 'paid'; // remember if it was paid
	$booking->update(['status' => 'cancelled']); // update status to cancelled
	if ($wasPaid) { // if it was paid
		$message = 'Booking cancelled! $' . $booking->total_price . ' has been refunded to your account.'; // refund message
	} else { // if it was not paid
		$message = 'Pending booking cancelled successfully.'; // pending cancel message
	} // close message selection
	return back()->with('success', $message); // return with the correct message
} // close method
```

**Your task**
Explain the difference between pending, paid, and cancelled.

## 14) Admin panel (fleet management)

**Concept**
Admins can add, edit, and delete cars. Think of it like a fleet control room.

**Why it matters**
You need a way to manage inventory without manual database edits.

**Code with comments**
```php
class IsAdmin // define the admin-only middleware
{ // open class body
	public function handle(Request $request, Closure $next): Response // handle each request
	{ // open method
		if (!auth()->check() || !auth()->user()->is_admin) { // block non-admins
			abort(403, 'Unauthorized action.'); // stop with forbidden error
		} // close block
		return $next($request); // allow the request to continue
	} // close method
} // close class
```

```php
class AdminController extends Controller // define the admin controller
{ // open class body
	public function index() // show the admin dashboard
	{ // open method
		$cars = Car::orderBy('id', 'desc')->get(); // load all cars, newest first
		$locations = Location::all(); // load all locations for dropdowns
		return view('admin.dashboard', ['cars' => $cars, 'locations' => $locations]); // show admin view
	} // close method
	public function storeCar(Request $request) // add a new car
	{ // open method
		$validated = $request->validate([ // validate input
			'type' => 'required|string|max:255', // require a car type
			'price_per_day' => 'required|numeric|min:0', // require a valid price
			'location_id' => 'required|integer|exists:locations,id', // require a valid location
		]); // close validation
		Car::create([ // create the car
			'type' => $validated['type'], // store type
			'price_per_day' => $validated['price_per_day'], // store price
			'location_id' => $validated['location_id'], // store location
			'is_available' => true, // default availability to true
		]); // close creation
		return back()->with('success', 'New car added to the fleet successfully!'); // return success
	} // close method
	public function editCar(Car $car) // show the edit form
	{ // open method
		$locations = Location::all(); // load locations for dropdown
		return view('admin.edit', ['car' => $car, 'locations' => $locations]); // show edit view
	} // close method
	public function updateCar(Request $request, Car $car) // update the car
	{ // open method
		$validated = $request->validate([ // validate input
			'type' => 'required|string|max:255', // require type
			'price_per_day' => 'required|numeric|min:0', // require price
			'location_id' => 'required|integer|exists:locations,id', // require location
			'is_available' => 'required|boolean', // require availability flag
		]); // close validation
		$car->update([ // update the car
			'type' => $validated['type'], // update type
			'price_per_day' => $validated['price_per_day'], // update price
			'location_id' => $validated['location_id'], // update location
			'is_available' => $validated['is_available'], // update availability
		]); // close update
		return redirect('/admin')->with('success', 'Car updated successfully!'); // return success
	} // close method
	public function destroyCar(Car $car) // delete the car
	{ // open method
		$car->delete(); // remove the car
		return back()->with('success', 'Car deleted permanently!'); // return success
	} // close method
} // close class
```

```blade
@extends('layouts.app') {{-- use shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Admin Panel</h1> {{-- page title --}}
	<p>Welcome to the control room! Only admins can see this.</p> {{-- admin notice --}}
	<h2>Manage Fleet</h2> {{-- section heading --}}
	<div style="background: #f9f9f9; padding: 15px; margin-bottom: 20px; border: 1px solid #ddd;"> {{-- form box --}}
		<h3>Add New Car</h3> {{-- form title --}}
		<form method="POST" action="/admin/cars"> {{-- submit to add car --}}
			@csrf {{-- CSRF token --}}
			<label>Type: <input type="text" name="type" required style="width: 100px;"></label> {{-- type input --}}
			<label style="margin-left: 10px;">Price/Day: $<input type="number" step="0.01" name="price_per_day" required style="width: 80px;"></label> {{-- price input --}}
			<label style="margin-left: 10px;">Location:  {{-- location label --}}
				<select name="location_id" required> {{-- location dropdown --}}
					<option value="">Select Location</option> {{-- placeholder option --}}
					@foreach($locations as $location) {{-- loop locations --}}
						<option value="{{ $location->id }}">{{ $location->name }}</option> {{-- option item --}}
					@endforeach {{-- end loop --}}
				</select> {{-- close dropdown --}}
			</label> {{-- close label --}}
			<button type="submit" style="margin-left: 10px;">Add Car</button> {{-- submit button --}}
		</form> {{-- close form --}}
	</div> {{-- close form box --}}
	<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;"> {{-- car list table --}}
		<thead> {{-- table header --}}
			<tr> {{-- header row --}}
				<th>#</th> {{-- row number --}}
				<th>Type</th> {{-- type column --}}
				<th>Price/Day</th> {{-- price column --}}
				<th>Available (Manual)</th> {{-- manual availability column --}}
				<th>Current Status (Dynamic)</th> {{-- dynamic status column --}}
				<th>Actions</th> {{-- actions column --}}
			</tr> {{-- end header row --}}
		</thead> {{-- close header --}}
		<tbody> {{-- table body --}}
			@foreach ($cars as $car) {{-- loop cars --}}
				<tr> {{-- open row --}}
					<td>{{ $loop->iteration }}</td> {{-- show row number --}}
					<td>{{ $car->type }} <small style="color:gray;">(ID: {{ $car->id }})</small></td> {{-- show type and id --}}
					<td>${{ $car->price_per_day }}</td> {{-- show price --}}
					<td>{{ $car->is_available ? 'Yes' : 'No' }}</td> {{-- show manual availability --}}
					<td> {{-- open status cell --}}
						@php $status = $car->getCurrentStatus(); @endphp {{-- calculate dynamic status --}}
						@if($status === 'Rented Out Right Now') {{-- check status --}}
							<span style="color: red; font-weight: bold;">{{ $status }}</span> {{-- red label --}}
						@elseif($status === 'Booked for Future') {{-- check status --}}
							<span style="color: orange; font-weight: bold;">{{ $status }}</span> {{-- orange label --}}
						@else {{-- default status --}}
							<span style="color: green; font-weight: bold;">{{ $status }}</span> {{-- green label --}}
						@endif {{-- end status check --}}
					</td> {{-- close status cell --}}
					<td> {{-- open actions cell --}}
						<a href="/admin/cars/{{ $car->id }}/edit" style="color: blue; margin-right: 10px; text-decoration: none;">Edit</a> {{-- edit link --}}
						<form method="POST" action="/admin/cars/{{ $car->id }}" style="display:inline;"> {{-- delete form --}}
							@csrf {{-- CSRF token --}}
							@method('DELETE') {{-- spoof DELETE method --}}
							<button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;" onclick="return confirm('Are you sure?')">Delete</button> {{-- delete button --}}
						</form> {{-- close delete form --}}
					</td> {{-- close actions cell --}}
				</tr> {{-- close row --}}
			@endforeach {{-- end loop --}}
		</tbody> {{-- close body --}}
	</table> {{-- close table --}}
@endsection {{-- end content section --}}
```

```blade
@extends('layouts.app') {{-- use the shared layout --}}
@section('content') {{-- start content section --}}
	<h1>Edit Car #{{ $car->id }}</h1> {{-- edit page title --}}
	<div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; max-width: 500px;"> {{-- form box --}}
		<form method="POST" action="/admin/cars/{{ $car->id }}"> {{-- submit update --}}
			@csrf {{-- CSRF token --}}
			@method('PUT') {{-- spoof PUT method --}}
			<div style="margin-bottom: 10px;"> {{-- type wrapper --}}
				<label>Type: <input type="text" name="type" value="{{ $car->type }}" required></label> {{-- type input --}}
			</div> {{-- close type wrapper --}}
			<div style="margin-bottom: 10px;"> {{-- price wrapper --}}
				<label>Price/Day: $<input type="number" step="0.01" name="price_per_day" value="{{ $car->price_per_day }}" required></label> {{-- price input --}}
			</div> {{-- close price wrapper --}}
			<div style="margin-bottom: 10px;"> {{-- location wrapper --}}
				<label>Location:  {{-- location label --}}
					<select name="location_id" required> {{-- location dropdown --}}
						@foreach($locations as $location) {{-- loop locations --}}
							<option value="{{ $location->id }}" {{ $car->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option> {{-- option with selected state --}}
						@endforeach {{-- end loop --}}
					</select> {{-- close dropdown --}}
				</label> {{-- close label --}}
			</div> {{-- close location wrapper --}}
			<div style="margin-bottom: 10px;"> {{-- availability wrapper --}}
				<label>Available:  {{-- availability label --}}
					<select name="is_available" required> {{-- availability dropdown --}}
						<option value="1" {{ $car->is_available ? 'selected' : '' }}>Yes</option> {{-- true option --}}
						<option value="0" {{ !$car->is_available ? 'selected' : '' }}>No</option> {{-- false option --}}
					</select> {{-- close dropdown --}}
				</label> {{-- close label --}}
				<small style="display: block; color: gray;">If the car is broken or booked, you can manually mark it "No" here.</small> {{-- help text --}}
			</div> {{-- close availability wrapper --}}
			<button type="submit" style="background: blue; color: white; padding: 5px 10px;">Update Car</button> {{-- update button --}}
			<a href="/admin" style="margin-left: 10px;">Cancel</a> {{-- cancel link --}}
		</form> {{-- close form --}}
	</div> {{-- close form box --}}
@endsection {{-- end content section --}}
```

**Your task**
Explain what the IsAdmin middleware does in one sentence.

## 15) Next steps

- Add real payment integration.
- Add email confirmations for bookings.
- Add car filters (type, location, availability) on the public listing page.
- Add booking conflict UI feedback.

---

If you want another deep dive section, tell me which file you want explained next.
