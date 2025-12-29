<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\BrowseMealController;
use App\Http\Controllers\ClaimController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // Staff Meal Management (CRUD)
    // ==========================================
    // Only staff members can access these routes (enforced by MealPolicy)
    Route::resource('meals', MealController::class)
        ->middleware('can:create,App\Models\Meal');

    // ==========================================
    // Browse Meals (Students & Staff)
    // ==========================================
    Route::get('/browse', [BrowseMealController::class, 'index'])->name('browse.index');
    Route::get('/browse/{meal}', [BrowseMealController::class, 'show'])->name('browse.show');

    // ==========================================
    // Claim Meals (Students only)
    // ==========================================
    // View user's claims
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    
    // Claim a meal with rate limiting to prevent abuse
    // Limit: 5 requests per 1 minute per user
    Route::post('/meals/{meal}/claim', [ClaimController::class, 'store'])
        ->name('claims.store')
        ->middleware('throttle:5,1');
    
    // Cancel a claim
    Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
});

require __DIR__.'/auth.php';
