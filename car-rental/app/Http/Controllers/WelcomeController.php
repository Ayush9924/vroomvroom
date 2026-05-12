<?php // app/Http/Controllers/WelcomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch 6 cars for the featured section
        $cars = Car::take(6)->get();

        return view('welcome', [
            'cars' => $cars
        ]);
    }
}
