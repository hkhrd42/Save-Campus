## Plan: Build Surplus Meal Redistribution System

Build a Laravel 11 application with role-based authentication where staff post surplus meals and students claim them. Focus on simplicity, security with policies, and safe concurrency handling using database transactions.

### Steps

1. **Install Laravel 11 + Breeze** — Create new Laravel project, install Breeze for authentication scaffolding, run migrations, and verify auth works at [/login](routes) and [/register](routes)

2. **Add role system** — Create migration to add `role` enum column (`staff`/`student`) to `users` table, create seeder with sample staff and student accounts for testing

3. **Create database structure** — Generate migrations for `meals` table (with `user_id`, `name`, `description`, `available_portions`, `pickup_location`, `expiration_date`) and `claims` table (with `user_id`, `meal_id`), set foreign key constraints

4. **Build Models with relationships** — Create `Meal` and `Claim` models, define relationships in `User`, `Meal`, `Claim` models (hasMany/belongsTo), add fillable properties

5. **Create MealPolicy for authorization** — Generate policy to restrict meal create/update/delete to staff only, register policy in `AuthServiceProvider`, use `authorize()` in controllers

6. **Build Controllers with transaction-safe claiming** — Create `MealController` (CRUD for staff) and `ClaimController` (claim action with `lockForUpdate()` transaction), add `MealRequest` for validation, apply `throttle:1,1` middleware to claim route

7. **Create Blade views with Tailwind** — Build [home page](resources/views) listing available meals, [staff dashboard](resources/views) with meal management table, [student dashboard](resources/views) with claimed meals list, meal create/edit forms using Breeze's Tailwind styling

8. **Add routes and test** — Define web routes with role middleware, ensure staff routes use `can:` middleware, test meal creation, claiming with multiple users, and verify concurrency safety

### Further Considerations

1. **Role assignment during registration** — Should users select their role at registration, or should you manually seed users? Recommend adding a dropdown on register form with `staff`/`student` options.

2. **Meal expiration display** — Show countdown or "expires in X hours" on meal cards for better UX? Can use Carbon's `diffForHumans()` in Blade.

3. **Sample data** — Want a seeder with 5-10 sample meals to test the interface immediately? Helps visualize the system without manual data entry.
