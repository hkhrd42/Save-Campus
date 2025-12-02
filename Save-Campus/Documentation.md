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

