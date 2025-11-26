
**Project Title:** Surplus Meal Redistribution System (Campus Cafeterias)
**Tech:** Laravel 11, PHP 8+, MySQL, HTML, CSS (no JS frameworks unless needed), Blade templates

**Goal:**
Build a simple but secure Laravel web application where **staff** can post surplus meals, and **students** can browse and claim available portions. Include basic security, authorization, and concurrency safety.

---

# **Requirements**

## ✅ **1. Authentication & Roles**

* Install Laravel Breeze for auth.
* Add a `role` column to users: `staff` or `student`.
* Use middleware or policies so:

  * **Staff** can create/edit/delete meals.
  * **Students** can view and claim meals only.

---

## ✅ **2. Database Schema**

Create migrations for:

### **users**

* id
* name
* email
* password
* role

### **meals**

* id
* user_id (staff owner)
* name
* description
* available_portions
* pickup_location
* expiration_date (datetime)
* created_at / updated_at

### **claims**

* id
* user_id (student)
* meal_id
* created_at

Set proper foreign keys.

---

## ✅ **3. Models & Relationships**

Implement:

**User.php**

* hasMany Meals
* hasMany Claims

**Meal.php**

* belongsTo User
* hasMany Claims

**Claim.php**

* belongsTo User
* belongsTo Meal

---

## ✅ **4. Views (Blade + HTML/CSS Only)**

Create simple interfaces using clean HTML + CSS, no heavy JS.

### Required pages:

* Home page showing available meals
* Staff dashboard (list + create/edit/delete meals)
* Student dashboard (claimed meals)
* Meal create/edit forms
* Claim button page

Use Tailwind (comes with Breeze) and simple clean UI.

---

## ✅ **5. Features to Implement**

### **Staff Capabilities**

* Login
* Create meals
* Edit meals
* Delete meals
* See list of claims for each meal

### **Student Capabilities**

* Login
* View all active meals (only meals with:

  * `available_portions > 0`
  * `expiration_date > now()`)
* Claim meals
* See a list of meals they claimed

---

## ✅ **6. Claim Logic (with Concurrency Safety)**

Important: Prevent multiple users from claiming the last portion at the same time.

Use a database transaction:

```
DB::transaction(function () use ($meal) {
    $meal->lockForUpdate();

    if ($meal->available_portions <= 0) {
        abort(400, 'Meal not available.');
    }

    $meal->available_portions -= 1;
    $meal->save();

    Claim::create([
        'user_id' => auth()->id(),
        'meal_id' => $meal->id,
    ]);
});
```

---

## ✅ **7. Security**

* Use Laravel Policies for meal management.
* Validate all forms (MealRequest).
* Escape all output in Blade (`{{ }}`).
* Add rate limiting to claim route to prevent spam.

```
Route::post('/meals/{meal}/claim', ...)
    ->middleware('throttle:1,1');
```

---

## ✅ **8. Automatic Meal Expiration**

No cron jobs.

Simply filter meals:

```
Meal::where('expiration_date', '>', now())
    ->where('available_portions', '>', 0)
    ->get();
```

---

## ✅ **9. Dashboard**

**Staff Dashboard:**

* Total meals posted
* Total portions distributed
* Table of meals + claim count

**Student Dashboard:**

* List of meals they claimed

---

## ✅ **10. Styling**

* Use simple HTML + CSS (or Tailwind from Breeze).
* Clean, minimal, mobile-friendly.
* No complex JS required.

---

# **Deliverables**

1. Fully working Laravel project.
2. All routes implemented.
3. Migrations, models, controllers, policies.
4. Blade templates (HTML/CSS).
5. Sample data seeders (optional).

---

# 📌 Additional Notes

* Keep everything simple and readable.
* No advanced frameworks.
* Focus on correct structure, security, and concurrency.

---
