<?php // allow PHP code to run in this file so Laravel can read routes
use Illuminate\Support\Facades\Route; // import the Route facade so we can register URL routes
use App\Http\Controllers\WelcomeController; // import the WelcomeController for the landing page
use App\Http\Controllers\AuthController; // import the AuthController so we can reference it directly
use App\Http\Controllers\LoginController; // import the LoginController so we can reference it directly
use App\Http\Controllers\BookingController; // import the BookingController so we can reference it directly
use App\Http\Controllers\PaymentController; // import the PaymentController for Razorpay integration

Route::get('/', [WelcomeController::class, 'index'])->name('home'); // send GET / to the WelcomeController index method
Route::get('/register', [AuthController::class, 'showRegister']); // send GET /register to show the registration form
Route::post('/register', [AuthController::class, 'storeRegister']); // send POST /register to handle form submission
Route::get('/login', [LoginController::class, 'showLogin'])->name('login'); // send GET /login to show the login form and name it for auth redirects
Route::post('/login', [LoginController::class, 'storeLogin']); // send POST /login to handle login submission
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // send POST /logout to log the user out safely
Route::get('/dashboard', function () { // define a dashboard route to demonstrate auth protection
	$bookings = auth()->user()->bookings()->with('car')->get(); // load the logged-in user's bookings with car details
	return view('dashboard', ['bookings' => $bookings]); // return the dashboard view with booking data
})->middleware('auth')->name('dashboard'); // require authentication and name the route

// Booking routes (auth protected)
Route::get('/bookings/create', [BookingController::class, 'showCreate'])->middleware('auth');
Route::post('/bookings/dates', [BookingController::class, 'storeDates'])->middleware('auth');
Route::get('/bookings/cars', [BookingController::class, 'showCars'])->middleware('auth');
Route::post('/bookings', [BookingController::class, 'store'])->middleware('auth');
Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])->middleware('auth');
Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->middleware('auth');

// Category browsing (auth protected)
Route::get('/categories', [BookingController::class, 'categories'])->middleware('auth')->name('categories.index');
Route::get('/categories/{slug}', [BookingController::class, 'categoryShow'])->middleware('auth')->name('categories.show');

// Razorpay Payment routes (auth protected)
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/create-order/{booking}', [PaymentController::class, 'createOrder'])->name('payment.createOrder');
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/payment/success/{booking}', [PaymentController::class, 'success'])->name('payment.success');
});

use App\Http\Controllers\AdminController; // import the AdminController so we can use it below
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () { // group all /admin URLs and protect them with our bouncer
    Route::get('/', [AdminController::class, 'index']); // send GET /admin to the AdminController index method
    Route::post('/cars', [AdminController::class, 'storeCar']); // send POST /admin/cars to handle adding a new car
    Route::get('/cars/{car}/edit', [AdminController::class, 'editCar']); // send GET to show the edit form
    Route::put('/cars/{car}', [AdminController::class, 'updateCar']); // send PUT to actually update the car in the database
    Route::delete('/cars/{car}', [AdminController::class, 'destroyCar']); // send DELETE /admin/cars/{id} to handle deleting a car
}); // close the admin group

