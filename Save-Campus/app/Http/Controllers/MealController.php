<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MealController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the staff's meals (staff dashboard).
     */
    public function index()
    {
        // Get all meals created by the authenticated staff member with claim counts
        $meals = Meal::where('user_id', auth()->id())
            ->withCount('claims')
            ->latest()
            ->paginate(10);

        // Calculate dashboard metrics
        // Get all meals (not paginated) for accurate totals
        $allMeals = Meal::where('user_id', auth()->id())
            ->withCount('claims')
            ->get();

        $totalMeals = $allMeals->count();
        $totalPortions = $allMeals->sum('available_portions');
        $totalClaims = $allMeals->sum('claims_count');

        return view('meals.index', compact('meals', 'totalMeals', 'totalPortions', 'totalClaims'));
    }

    /**
     * Show the form for creating a new meal.
     */
    public function create()
    {
        $this->authorize('create', Meal::class);
        
        return view('meals.create');
    }

    /**
     * Store a newly created meal in storage.
     */
    public function store(StoreMealRequest $request)
    {
        // Validation and authorization already handled by StoreMealRequest
        
        $meal = Meal::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'available_portions' => $request->available_portions,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('meals.index')
            ->with('success', 'Meal created successfully!');
    }

    /**
     * Display the specified meal.
     */
    public function show(Meal $meal)
    {
        // Load the claims with user information
        $meal->load(['claims.user']);
        
        return view('meals.show', compact('meal'));
    }

    /**
     * Show the form for editing the specified meal.
     */
    public function edit(Meal $meal)
    {
        $this->authorize('update', $meal);
        
        return view('meals.edit', compact('meal'));
    }

    /**
     * Update the specified meal in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        // Validation and authorization already handled by UpdateMealRequest
        
        $meal->update($request->validated());

        return redirect()->route('meals.show', $meal)
            ->with('success', 'Meal updated successfully!');
    }

    /**
     * Remove the specified meal from storage.
     */
    public function destroy(Meal $meal)
    {
        $this->authorize('delete', $meal);
        
        // Check if there are any claims
        if ($meal->claims()->count() > 0) {
            return redirect()->route('meals.index')
                ->with('error', 'Cannot delete meal with existing claims.');
        }
        
        $meal->delete();

        return redirect()->route('meals.index')
            ->with('success', 'Meal deleted successfully!');
    }
}
