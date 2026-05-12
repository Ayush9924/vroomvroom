<?php // allow PHP code in this controller file so Laravel can load the class
namespace App\Http\Controllers; // declare this class lives in the Controllers namespace for autoloading
use Illuminate\Http\Request; // import the Request class so we can read form input later
use App\Models\Booking; // import the Booking model so we can create new bookings
use App\Models\Car; // import the Car model so we can look up car pricing
use App\Models\CarCategory;
use Illuminate\Support\Facades\Auth; // import Auth so we can attach the booking to the logged-in user
use Carbon\Carbon; // import Carbon so we can calculate date differences
class BookingController extends Controller // define a controller class to handle booking actions
{ // open the class body so we can add methods inside it
    public function showCreate() // create a method that displays the booking form
    { // open the method body so we can return a response
        return view('bookings.create'); // return the booking form view (just dates now!)
    } // close the method body to finish the showCreate action

    public function storeDates(Request $request) // Step 1: Save the dates to the session
    {
        $validated = $request->validate([ // validate the dates first
            'start_date' => 'required|date|after_or_equal:today', // must be a real date today or in the future
            'end_date' => 'required|date|after_or_equal:start_date', // must be a real date after the start date
        ]);

        // Save the dates into the user's "Session" (a temporary storage backpack)
        session(['booking_start_date' => $validated['start_date']]);
        session(['booking_end_date' => $validated['end_date']]);

        return redirect()->route('categories.index');
    }
    public function showCars() // Step 2: Show available cars
    {
        $start = session('booking_start_date'); // pull the start date out of the backpack
        $end = session('booking_end_date'); // pull the end date out of the backpack

        if (!$start || !$end) { // if the backpack is empty (user skipped step 1)
            return redirect('/bookings/create'); // send them back to step 1
        }

        // ADVANCED QUERY: Find cars where `is_available` is true AND they DO NOT have any ACTIVE bookings that overlap these dates
        $availableCars = Car::where('is_available', true)
            ->whereDoesntHave('bookings', function ($query) use ($start, $end) {
                $query->where('status', '!=', 'cancelled') // IGNORE CANCELLED BOOKINGS!
                      ->where(function ($q) use ($start, $end) {
                          $q->whereBetween('start_date', [$start, $end])
                            ->orWhereBetween('end_date', [$start, $end])
                            ->orWhere(function ($q2) use ($start, $end) {
                                $q2->where('start_date', '<=', $start)
                                   ->where('end_date', '>=', $end);
                            });
                      });
            })->get();

        return view('bookings.cars', ['cars' => $availableCars, 'start' => $start, 'end' => $end]); // pass the cars and dates to the view
    }

    public function categories()
    {
        $categories = CarCategory::withCount('cars')->get();
        $start_date = session('booking_start_date');
        $end_date = session('booking_end_date');
        return view('bookings.categories', compact('categories', 'start_date', 'end_date'));
    }

    public function categoryShow($slug)
    {
        $category = CarCategory::where('slug', $slug)->firstOrFail();
        $start_date = session('booking_start_date');
        $end_date = session('booking_end_date');

        if ($start_date && $end_date) {
            $cars = Car::where('category_id', $category->id)
                ->where('is_available', true)
                ->whereDoesntHave('bookings', function ($query) use ($start_date, $end_date) {
                    $query->where('status', '!=', 'cancelled')
                          ->where(function ($q) use ($start_date, $end_date) {
                              $q->whereBetween('start_date', [$start_date, $end_date])
                                ->orWhereBetween('end_date', [$start_date, $end_date])
                                ->orWhere(function ($q2) use ($start_date, $end_date) {
                                    $q2->where('start_date', '<=', $start_date)
                                       ->where('end_date', '>=', $end_date);
                                });
                          });
                })->get();
        } else {
            $cars = Car::where('category_id', $category->id)->get();
        }

        return view('bookings.category-show', compact('category', 'cars', 'start_date', 'end_date'));
    }

    public function store(Request $request) // create a method that handles the booking form submission
    { // open the method body so we can return a response
        $validated = $request->validate([ // validate the incoming booking data
            'car_id' => 'required|integer|exists:cars,id', // require a valid car id
        ]); 

        $startStr = session('booking_start_date'); // pull dates from the backpack
        $endStr = session('booking_end_date');

        if (!$startStr || !$endStr) { return redirect('/bookings/create'); } // safety check

        $car = Car::findOrFail($validated['car_id']); // load the car

        if (!$car->is_available) { // check if the car is currently available to book
            return back()->withErrors(['car_id' => 'This car is not available right now.']); 
        } 

        // Overlap check as a final safety measure — IGNORE cancelled bookings!
        $overlapExists = Booking::where('car_id', $car->id)
            ->where('status', '!=', 'cancelled') // cancelled bookings free up the slot!
            ->where(function ($query) use ($startStr, $endStr) { 
                $query->whereBetween('start_date', [$startStr, $endStr]) 
                    ->orWhereBetween('end_date', [$startStr, $endStr]) 
                    ->orWhere(function ($query) use ($startStr, $endStr) { 
                        $query->where('start_date', '<=', $startStr) 
                            ->where('end_date', '>=', $endStr); 
                    }); 
            })->exists(); 

        if ($overlapExists) { 
            return back()->withErrors(['car_id' => 'Someone just booked this car! Please pick another.']); 
        } 

        $start = Carbon::parse($startStr); // parse the start date
        $end = Carbon::parse($endStr); // parse the end date
        $days = $start->diffInDays($end) + 1; // count booking days
        $totalPrice = $days * $car->price_per_day; // calculate total price

        Booking::create([ // create a new booking record
            'user_id' => Auth::id(), 
            'car_id' => $car->id, 
            'start_date' => $startStr, // use session date
            'end_date' => $endStr, // use session date
            'total_price' => $totalPrice, 
            'status' => 'pending', 
        ]); 

        // CLEAR the session backpack so they don't accidentally reuse these dates later
        session()->forget(['booking_start_date', 'booking_end_date']);

        return redirect('/dashboard')->with('success', 'Booking created! Please pay to confirm.'); // send the user home
    } // close the method body
    public function pay(Booking $booking) // create a method to handle the fake payment, auto-loading the booking by ID
    { // open the method body so we can process the payment
        if ($booking->user_id !== Auth::id()) { // check if the logged-in user actually owns this booking
            abort(403); // stop the action and show a 'Forbidden' error if they don't own it
        } // close the security check block
        $booking->update(['status' => 'paid']); // update the booking status in the database to paid
        return back()->with('success', 'Payment successful! Your car is reserved.'); // send them back to the dashboard with a success message
    } // close the method body to finish the pay action

    public function cancel(Booking $booking) // create a method to handle cancellation
    {
        if ($booking->user_id !== Auth::id()) { // security check: do they own this booking?
            abort(403);
        }

        if ($booking->status === 'cancelled') { // check if it's already cancelled
            return back()->with('error', 'This booking is already cancelled.');
        }

        // Check if they had already paid so we know whether to issue a "refund"
        $wasPaid = $booking->status === 'paid';

        // Update the status to 'cancelled'
        $booking->update(['status' => 'cancelled']);

        // Create the right success message based on their previous payment status
        if ($wasPaid) {
            $message = 'Booking cancelled! $' . $booking->total_price . ' has been refunded to your account.';
        } else {
            $message = 'Pending booking cancelled successfully.';
        }

        return back()->with('success', $message); // send them back with the message
    }
}
