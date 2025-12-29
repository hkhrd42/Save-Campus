<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class BrowseMealController extends Controller
{
    /**
     * Display a listing of active meals for students to browse.
     */
    public function index()
    {
        // Get only active (non-expired, available) meals
        $meals = Meal::where('available_portions', '>', 0)
            ->where('expires_at', '>', now())
            ->with('staff') // Eager load the staff member
            ->latest()
            ->paginate(12);

        return view('browse.index', compact('meals'));
    }

    /**
     * Display the specified meal details.
     */
    public function show(Meal $meal)
    {
        // Load the staff member who posted it
        $meal->load('staff');
        
        // Check if the current user has already claimed this meal
        $hasClaimed = false;
        if (auth()->check()) {
            $hasClaimed = $meal->claims()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('browse.show', compact('meal', 'hasClaimed'));
    }
}
