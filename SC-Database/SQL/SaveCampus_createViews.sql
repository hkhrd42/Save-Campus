USE SaveCampus;
GO

-- View to list all not expired and available meals
CREATE VIEW vw_ActiveMeals AS
SELECT 
    MealID,
    MealName,
    mealDescription,
    availableportion,
    expiryDate,
    CreatedAt,
    UpdatedAt,
    u.Username AS StaffUsername,
    u.Email AS StaffEmail
FROM Meals m
JOIN Users u ON m.staffID = u.UserID
WHERE m.expiryDate >= GETDATE() AND m.availableportion > 0;


-- View to list all staff members meals and their claim status
CREATE VIEW StaffMealStats AS
SELECT 
    u.user_id AS staff_id,
    u.full_name AS staff_name,
    m.meal_id,
    m.name AS meal_name,
    m.available_portions,
    m.expires_at,
    COUNT(c.claim_id) AS total_claims
FROM Users u
JOIN Meals m ON m.staff_id = u.user_id
LEFT JOIN Claims c ON c.meal_id = m.meal_id
WHERE u.role = 'staff'
GROUP BY 
    u.user_id, u.full_name,
    m.meal_id, m.name, 
    m.available_portions, m.expires_at;
GO


-- list all claims made by students
CREATE VIEW StudentClaimHistory AS
SELECT 
    u.user_id AS student_id,
    u.full_name AS student_name,
    c.claim_id,
    c.claimed_at,
    m.meal_id,
    m.name AS meal_name,
    m.expires_at
FROM Claims c
JOIN Users u ON u.user_id = c.student_id
JOIN Meals m ON m.meal_id = c.meal_id
WHERE u.role = 'student';
GO

-- list meals that are claimed or expired
CREATE VIEW ExpiredMeals AS
SELECT 
    m.meal_id,
    m.name,
    m.available_portions,
    m.expires_at,
    m.staff_id,
    u.full_name AS staff_name
FROM Meals m
JOIN Users u ON u.user_id = m.staff_id
WHERE m.available_portions <= 0
   OR m.expires_at <= GETDATE();
GO
