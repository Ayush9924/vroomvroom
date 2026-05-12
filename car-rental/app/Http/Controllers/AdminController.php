<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Car; // import the Car model so we can load cars for the admin
use App\Models\Location; // import the Location model so we can load available locations
use App\Models\CarCategory;

class AdminController extends Controller
{ // open the class body
    public function index() // create a method to show the main admin panel
    { // open the method body
        $cars = Car::orderBy('id', 'desc')->get(); // load all cars in the system, newest first
        $locations = Location::all(); // load all locations so the admin can pick one when adding a car
        $categories = CarCategory::all();
        return view('admin.dashboard', ['cars' => $cars, 'locations' => $locations, 'categories' => $categories]); // return a special admin view and pass the data
    } // close the method body

    public function storeCar(Request $request) // handle the form submission
    { // open the method body
        $validated = $request->validate([ // check the incoming data
            'type' => 'required|string|max:255', // require a valid string for car type
            'price_per_day' => 'required|numeric|min:0', // require a valid number for price
            'location_id' => 'required|integer|exists:locations,id', // require a valid location from the database
            'category_id' => 'nullable|integer|exists:car_categories,id',
            'car_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:2000|max:2026',
            'color' => 'nullable|string|max:255',
            'seats' => 'nullable|integer|min:2|max:8',
            'transmission' => 'nullable|string|in:Automatic,Manual',
            'fuel_type' => 'nullable|string|in:Petrol,Diesel,Electric,Hybrid',
            'mileage' => 'nullable|string|max:255',
        ]); // close the validation rules

        Car::create([ // tell the Car model to create a new row in the database
            'type' => $validated['type'], // save the type
            'price_per_day' => $validated['price_per_day'], // save the price
            'location_id' => $validated['location_id'], // save the location
            'category_id' => $validated['category_id'] ?? null,
            'car_name' => $validated['car_name'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'year' => $validated['year'] ?? null,
            'color' => $validated['color'] ?? null,
            'seats' => $validated['seats'] ?? 5,
            'transmission' => $validated['transmission'] ?? null,
            'fuel_type' => $validated['fuel_type'] ?? null,
            'mileage' => $validated['mileage'] ?? null,
            'is_available' => true, // newly added cars should default to available
        ]); // close the creation array

        return back()->with('success', 'New car added to the fleet successfully!'); // send them back with a flash message!
    } // close the method body

    public function destroyCar(Car $car) // auto-load the car by ID
    { // open the method body
        $car->delete(); // permanently delete the car from the database
        return back()->with('success', 'Car deleted permanently!'); // redirect back with a success message
    } // close the method body

    public function editCar(Car $car) // auto-load the car to edit
    { // open the method body
        $locations = Location::all(); // load locations for the dropdown
        $categories = CarCategory::all(); // load categories for the dropdown
        return view('admin.edit', ['car' => $car, 'locations' => $locations, 'categories' => $categories]); // return the edit view
    } // close the method body

    public function updateCar(Request $request, Car $car) // handle the PUT request
    { // open the method body
        $validated = $request->validate([ // validate the incoming data
            'type'         => 'required|string|max:255',
            'price_per_day'=> 'required|numeric|min:0',
            'location_id'  => 'required|integer|exists:locations,id',
            'is_available' => 'required|boolean',
            'category_id'  => 'nullable|integer|exists:car_categories,id',
            'car_name'     => 'nullable|string|max:255',
            'brand'        => 'nullable|string|max:255',
            'year'         => 'nullable|integer|min:2000|max:2030',
            'color'        => 'nullable|string|max:255',
            'seats'        => 'nullable|integer|min:2|max:8',
            'transmission' => 'nullable|string|in:Automatic,Manual',
            'fuel_type'    => 'nullable|string|in:Petrol,Diesel,Electric,Hybrid',
            'mileage'      => 'nullable|string|max:255',
        ]);

        $car->update([
            'type'         => $validated['type'],
            'price_per_day'=> $validated['price_per_day'],
            'location_id'  => $validated['location_id'],
            'is_available' => $validated['is_available'],
            'category_id'  => $validated['category_id'] ?? $car->category_id,
            'car_name'     => $validated['car_name'] ?? $car->car_name,
            'brand'        => $validated['brand'] ?? $car->brand,
            'year'         => $validated['year'] ?? $car->year,
            'color'        => $validated['color'] ?? $car->color,
            'seats'        => $validated['seats'] ?? $car->seats,
            'transmission' => $validated['transmission'] ?? $car->transmission,
            'fuel_type'    => $validated['fuel_type'] ?? $car->fuel_type,
            'mileage'      => $validated['mileage'] ?? $car->mileage,
        ]);

        return redirect('/admin')->with('success', 'Car #'.$car->id.' updated successfully!');
    } // close the method body
}
