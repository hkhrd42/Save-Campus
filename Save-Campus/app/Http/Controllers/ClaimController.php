<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    /**
     * Display a listing of the user's claims.
     */
    public function index()
    {
        $claims = auth()->user()
            ->claims()
            ->with('meal.staff')
            ->latest()
            ->paginate(10);

        return view('claims.index', compact('claims'));
    }

    /**
     * Store a newly created claim (claim a meal).
     * 
     * CRITICAL: Uses database transactions and row locking to prevent race conditions.
     * This ensures that two users cannot claim the last portion simultaneously.
     */
    public function store(Request $request, Meal $meal)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to claim a meal.');
        }

        // Check if student (not staff)
        if (auth()->user()->role === 'staff') {
            return redirect()->back()
                ->with('error', 'Staff members cannot claim meals.');
        }

        // Check if user has already claimed this meal
        $existingClaim = Claim::where('user_id', auth()->id())
            ->where('meal_id', $meal->id)
            ->exists();

        if ($existingClaim) {
            return redirect()->back()
                ->with('error', 'You have already claimed this meal.');
        }

        try {
            // CRITICAL SECTION: Database Transaction with Row Locking
            DB::transaction(function () use ($meal) {
                // lockForUpdate() locks this specific meal row until transaction completes
                // Other users trying to claim will wait in queue
                $lockedMeal = Meal::where('id', $meal->id)
                    ->lockForUpdate()
                    ->first();

                // Check if meal is expired
                if ($lockedMeal->expires_at->isPast()) {
                    throw new \Exception('This meal has expired.');
                }

                // Check if portions are available
                if ($lockedMeal->available_portions < 1) {
                    throw new \Exception('No portions left.');
                }

                // Decrement available portions
                $lockedMeal->available_portions -= 1;
                $lockedMeal->save();

                // Create the claim
                Claim::create([
                    'user_id' => auth()->id(),
                    'meal_id' => $lockedMeal->id,
                ]);
            });

            return redirect()->route('claims.index')
                ->with('success', 'Meal claimed successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a claim (unclaim a meal).
     */
    public function destroy(Claim $claim)
    {
        // Ensure the user owns this claim
        if ($claim->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::transaction(function () use ($claim) {
                // Lock the meal row
                $meal = Meal::where('id', $claim->meal_id)
                    ->lockForUpdate()
                    ->first();

                // Increment portions back
                $meal->available_portions += 1;
                $meal->save();

                // Delete the claim
                $claim->delete();
            });

            return redirect()->route('claims.index')
                ->with('success', 'Claim cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel claim.');
        }
    }
}
