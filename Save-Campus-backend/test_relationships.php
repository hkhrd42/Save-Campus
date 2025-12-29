<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Meal;
use App\Models\Claim;

echo "=== Creating Test Data ===" . PHP_EOL;

// Create a staff user
$staff = User::create([
    'name' => 'John Staff',
    'email' => 'staff@campus.edu',
    'password' => bcrypt('password123'),
    'role' => 'staff'
]);
echo "✓ Staff user created: {$staff->name} (ID: {$staff->id})" . PHP_EOL;

// Create a student user
$student = User::create([
    'name' => 'Jane Student',
    'email' => 'student@campus.edu',
    'password' => bcrypt('password123'),
    'role' => 'student'
]);
echo "✓ Student user created: {$student->name} (ID: {$student->id})" . PHP_EOL;

// Create a meal posted by staff
$meal = Meal::create([
    'user_id' => $staff->id,
    'name' => 'Pizza Slices',
    'description' => 'Fresh pizza from cafeteria event',
    'available_portions' => 10,
    'expires_at' => now()->addHours(3)
]);
echo "✓ Meal created: {$meal->name} (ID: {$meal->id})" . PHP_EOL;

// Student claims the meal
$claim = Claim::create([
    'meal_id' => $meal->id,
    'user_id' => $student->id
]);
echo "✓ Claim created: Student {$student->name} claimed {$meal->name}" . PHP_EOL;

echo PHP_EOL . "=== Testing Relationships ===" . PHP_EOL;

// Test User -> Meals relationship
echo "✓ Staff posted meals: {$staff->meals->count()} meal(s)" . PHP_EOL;

// Test Meal -> User relationship
echo "✓ Meal posted by: {$meal->staff->name}" . PHP_EOL;

// Test Meal -> Claims relationship
echo "✓ Meal has claims: {$meal->claims->count()} claim(s)" . PHP_EOL;

// Test User -> Claims relationship
echo "✓ Student made claims: {$student->claims->count()} claim(s)" . PHP_EOL;

// Test Claim -> Meal relationship
echo "✓ Claim is for meal: {$claim->meal->name}" . PHP_EOL;

// Test Claim -> User relationship
echo "✓ Claim made by: {$claim->student->name}" . PHP_EOL;

// Test isExpired method
$expired = $meal->isExpired() ? 'Yes' : 'No';
echo "✓ Meal is expired: {$expired}" . PHP_EOL;

echo PHP_EOL . "=== All Relationships Working! ===" . PHP_EOL;
