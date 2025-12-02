# DataBase

first need to create three tables for users(student staff), Meals, Claims.

Users is already created just add some lines to distinguesh between student and staff

Create a Meals Table by this command
    php artisan make:migration create_meals_table

add needed columns to the table

Create a Claims Table by this command
    php artisan make:migration create_claims_table
add needed columns to the table

Database Structure Overview:
1️⃣ Users Table ✓
id: Unique identifier for each user
name: User's full name
email: User's email (unique)
role: Either 'staff' or 'student' (default: 'student')
password: Encrypted password
timestamps: created_at, updated_at
Purpose: Stores all users - both staff who post meals and students who claim them.

2️⃣ Meals Table ✓
id: Unique identifier for each meal
user_id: Links to the staff member who posted it (foreign key → users.id)
name: Meal name (e.g., "Pizza slices")
description: Optional details about the meal
available_portions: How many servings are available
pickup_location: Where students can collect the food (added in migration)
expires_at: Date/time when meal expires
timestamps: created_at, updated_at
Purpose: Staff post leftover meals here for students to claim.

3️⃣ Claims Table ✓
id: Unique identifier for each claim
meal_id: Links to the meal being claimed (foreign key → meals.id)
user_id: Links to the student claiming it (foreign key → users.id)
timestamps: created_at, updated_at
Purpose: Tracks which students have claimed which meals.

# Modelrelationship
Models are PHP classes that represent your database tables. They allow you to interact with the database using simple PHP code instead of writing SQL queries.

User Model = users table
Meal Model = meals table
Claim Model = claims table

What $fillable does:
Lists which fields can be mass-assigned (filled in bulk)
Security feature - prevents users from modifying fields you didn't intend

belongsTo = This meal belongs to ONE staff member
hasMany = This meal can have MANY claims

USER (Staff)
    ↓ hasMany
  MEALS
    ↓ hasMany
  CLAIMS
    ↓ belongsTo
USER (Student)

# Policies
What are Policies?
Policies are Laravel's way of organizing authorization logic. They determine whether a user can perform specific actions (like create, view, update, delete) on a resource. This keeps authorization logic centralized and reusable, separate from your controllers.

How They Work
Policy Methods: Each method checks if a user has permission for an action
Automatic Resolution: Laravel can automatically apply policies when you use authorize() in controllers or @can in views
Clean Controllers: Your controllers stay clean - just call $this->authorize('update', $meal) instead of writing if-statements
What This Policy Does
create: Only staff members can create meals
update: Only the user who created the meal can update it
delete: Only the user who created the meal can delete it

command: 
php artisan make:policy MealPolicy --model=Meal

# Validation

Form Requests are custom request classes that encapsulate validation logic. Instead of cluttering your controllers with validation rules, you move them to dedicated classes.

How It Works
Request hits your controller method
Laravel automatically runs the Form Request validation before the controller method executes
If validation fails → automatically redirects back with errors
If validation passes → controller method receives the validated data

commanda: 
php artisan make:request StoreMealRequest
php artisan make:request UpdateMealRequest

Benefits
Clean Controllers: Controllers stay focused on business logic, not validation
Reusable: Same validation rules can be used across multiple places
Authorization: Can include authorization logic alongside validation
Automatic Validation: Laravel automatically validates before reaching your controller method
Type Safety: Better IDE autocomplete and type hinting

What Happens Automatically
Before Controller Method Runs:

✅ Checks authorization (staff only for create, owner only for update)
✅ Validates all input data
✅ If fails: redirects back with errors
✅ If passes: controller method executes
Error Handling:

Automatically redirects back to form
Keeps old input values
Shows custom error messages

# Controllers
command :  php artisan make:controller MealController --resource
           php artisan make:controller BrowseMealController
           php artisan make:controller ClaimController

Controllers handle the application logic between routes and models. They receive requests, interact with models, and return responses (views or redirects).

MVC Pattern
Model: Database interactions (Meal, Claim, User)
View: What users see (Blade templates)
Controller: Business logic connecting them
The Three Controllers
1. MealController (Staff Management)
Staff members manage their meals (CRUD operations)

2. BrowseMealController (Student View)
Students browse and view available meals (read-only)

3. ClaimController (Critical Concurrency)
Handles meal claiming with race condition prevention

Why Transactions + lockForUpdate?
The Problem (Race Condition)
The Solution:
lockForUpdate() locks the database row until transaction completes, forcing users to wait in line.

Validation & Security
Built-in Protections:

✅ Authentication: Must be logged in
✅ Role checking: Staff can't claim, students can't create
✅ Double-claim prevention: One meal per user
✅ Expiration checks: Can't claim expired meals
✅ Concurrency safety: lockForUpdate() prevents race conditions
✅ Authorization policies: Automatic via Form Requests

| Method     | Route               | Description                                   |
|------------|---------------------|-----------------------------------------------|
| `index()`  | `GET /claims`       | User's claim history                          |
| `store()`  | `POST /claims/{meal}` | **CRITICAL:** Claim a meal with transaction locking |
| `destroy()`| `DELETE /claims/{claim}` | Cancel a claim, restore portion               |

# Routes
Routes map HTTP requests (URLs) to controller actions. They define what happens when users visit specific URLs in your application.

Route Components Explained
1. Route Groups with Middleware
Applies auth middleware to ALL routes inside
Users must be logged in to access any of these routes

2. Resource Routes
Automatically creates 7 RESTful routes:
GET /meals → index()
GET /meals/create → create()
POST /meals → store()
GET /meals/{meal} → show()
GET /meals/{meal}/edit → edit()
PUT/PATCH /meals/{meal} → update()
DELETE /meals/{meal} → destroy()

3. Inline Authorization
Checks the MealPolicy::create() method
Only staff can access meal management routes

4. Rate Limiting
Limits to 5 requests per 1 minute per user
Prevents spam/abuse when claiming meals
Returns 429 (Too Many Requests) if exceeded

command: php artisan route:list --except-vendor

web.php
├── Public Routes
│   └── GET / → Welcome page
│
└── Authenticated Routes (auth middleware)
    ├── Profile Management
    │   ├── GET /profile → Edit profile
    │   ├── PATCH /profile → Update profile
    │   └── DELETE /profile → Delete profile
    │
    ├── Staff Meal Management (can:create,Meal)
    │   ├── GET /meals → meals.index (list all my meals)
    │   ├── GET /meals/create → meals.create (create form)
    │   ├── POST /meals → meals.store (save new meal)
    │   ├── GET /meals/{meal} → meals.show (view meal)
    │   ├── GET /meals/{meal}/edit → meals.edit (edit form)
    │   ├── PUT/PATCH /meals/{meal} → meals.update (save changes)
    │   └── DELETE /meals/{meal} → meals.destroy (delete meal)
    │
    ├── Browse Meals (All authenticated users)
    │   ├── GET /browse → browse.index (list active meals)
    │   └── GET /browse/{meal} → browse.show (view meal details)
    │
    └── Claim Management (Students)
        ├── GET /claims → claims.index (my claims)
        ├── POST /meals/{meal}/claim → claims.store (claim meal) [RATE LIMITED]
        └── DELETE /claims/{claim} → claims.destroy (cancel claim)

🔒 Security Layers
Layer 1: Authentication
✅ All routes require login
Redirects to login page if not authenticated
Layer 2: Authorization (Policy-Based)
✅ Only staff can access /meals routes
Uses MealPolicy::create() to check role === 'staff'
Returns 403 Forbidden if not authorized
Layer 3: Rate Limiting
✅ Limits claiming to 5 attempts per minute
Prevents spam/bot abuse
Returns 429 Too Many Requests if exceeded

Request → auth → throttle → can → Controller → Response
   ↓         ↓        ↓         ↓         ↓
Logged?  Rate OK?  Policy?  Execute   Return

oute Behavior Examples
Example 1: Staff Creating a Meal
1. User visits: GET /meals/create
2. Checks: ✅ Logged in? → ✅ Is staff? → Show form
3. User submits: POST /meals
4. Validates: StoreMealRequest
5. Authorizes: MealPolicy::create()
6. Creates meal → Redirect to /meals
Example 2: Student Claiming a Meal
1. User clicks "Claim": POST /meals/5/claim
2. Checks: ✅ Logged in? → ✅ Under rate limit?
3. Controller: ClaimController::store()
4. Validates: Not staff, not duplicate, has portions
5. Transaction + Lock: Decrement portions
6. Creates claim → Redirect to /claims

# Blades
Blade is Laravel's templating engine that makes it easy to create dynamic HTML. It allows you to:

Use variables: {{ $meal->name }}
Control structures: @if, @foreach, @auth
Extend layouts: @extends('layouts.app')
Include components: <x-button>

MVC Flow with Views
Request → Route → Controller → View → Response
                      ↓          ↓
                   Gets Data   Displays Data

resources/views/
├── layouts/
│   └── app.blade.php (✅ Already exists from Breeze)
│
├── meals/ (Staff Views - CRUD)
│   ├── index.blade.php ✅ Staff dashboard
│   ├── create.blade.php ✅ Create meal form
│   ├── edit.blade.php ✅ Edit meal form
│   └── show.blade.php ✅ View meal details + claims list
│
├── browse/ (Student Views - Read Only)
│   ├── index.blade.php ✅ Browse active meals (grid)
│   └── show.blade.php ✅ Meal details + claim button
│
└── claims/
    └── index.blade.php ✅ User's claimed meals

    1. Staff Views (meals/)
index.blade.php (Staff Dashboard)
Purpose: Staff manage their posted meals

✅ Table view with all staff's meals
✅ Shows portions left, claims count, expiration
✅ Status badges (Active/Expired)
✅ Actions: View, Edit, Delete
✅ Pagination support
✅ Empty state with "Add Meal" CTA
create.blade.php (Create Meal Form)
Purpose: Staff post new meals

✅ Form fields: Name, Description, Portions, Expiration
✅ Field validation with error messages
✅ @error directive shows validation errors
✅ old() helper preserves input on error
✅ Cancel button returns to dashboard
edit.blade.php (Edit Meal Form)
Purpose: Staff update existing meals

✅ Pre-filled form with existing data
✅ Shows current claim count
✅ Same validation as create
✅ PUT method for update
show.blade.php (Meal Details for Staff)
Purpose: View meal details and who claimed it

✅ Full meal information
✅ List of all claims with student info
✅ Student name, email, claim timestamp
✅ Edit button for quick access
2. Student Views (browse/)
index.blade.php (Browse Meals)
Purpose: Students discover available meals

✅ Grid layout (responsive: 1/2/3 columns)
✅ Cards with meal info
✅ Shows: portions left, staff name, expiration
✅ "View Details" button on each card
✅ Filters: Only active, non-expired meals
✅ Pagination support
✅ Empty state when no meals
show.blade.php (Meal Details for Students)
Purpose: View meal details and claim it

✅ Full meal information display
✅ Staff contact info
✅ Smart claim button with conditions:
✅ Shows "Claim" button if eligible
✅ Shows "Already claimed" if duplicate
✅ Shows "Expired" if no longer available
✅ Shows "Staff cannot claim" warning
✅ Visual status indicators
✅ Rate-limited form submission
3. Claims View (claims/)
index.blade.php (My Claims)
Purpose: Students see their claimed meals

✅ Grid of claimed meals
✅ Shows meal info, staff name, expiration
✅ Status badge (Active/Expired)
✅ "View" button to see meal details
✅ "Cancel" button to unclaim (if not expired)
✅ Confirmation dialog on cancel
✅ Empty state with "Browse Meals" CTA

# Dahsboards
Dashboard Metrics are summary statistics that give staff members a quick overview of their meal management activity. Instead of just showing a list of meals, we display key performance indicators (KPIs) at the top.

The Three Key Metrics
1. Total Meals Posted
How many meals has this staff member created?

2. Total Portions Available
Sum of all available_portions across all meals

3. Total Claims
Sum of all claims made on this staff member's meals