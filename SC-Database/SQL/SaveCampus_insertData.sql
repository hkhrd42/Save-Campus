USE SaveCampus;
GO

INSERT INTO Users (Username, PasswordHash, Email, UserRole)
VALUES 
    ('john.staff', '$2a$10$XqjHGvVoWKjQ8YvK9xZH4eK9YvK9xZH4eK9YvK9xZH4eK9YvK9xZH', 'john.staff@uni.edu', 'staff'),
    ('maria.chef', '$2a$10$AbcDefGhIjKlMnOpQrStUvWxYz123456789AbcDefGhIjKlMn', 'maria.chef@uni.edu', 'staff'),
    ('robert.cook', '$2a$10$ZyXwVuTsRqPoNmLkJiHgFeDcBaZyXwVuTsRqPoNmLkJiHgFe', 'robert.cook@uni.edu', 'staff'),
    ('lisa.manager', '$2a$10$PlOkMiJuHyGtFrEdSwQaZxCvBnMlKjIhGfEdCbAzYxWvUt', 'lisa.manager@uni.edu', 'staff');

INSERT INTO Users (Username, PasswordHash, Email, UserRole)
VALUES 
    ('sarah.student', '$2a$10$StUdEnT1234567890AbCdEfGhIjKlMnOpQrStUvWxYzAbCd', 'sarah.student@uni.edu', 'student'),
    ('mike.jones', '$2a$10$MiKeJoNeS987654321ZyXwVuTsRqPoNmLkJiHgFeDcBa', 'mike.jones@uni.edu', 'student'),
    ('emily.brown', '$2a$10$EmIlYbRoWn456789123AbCdEfGhIjKlMnOpQrStUvWxYz', 'emily.brown@uni.edu', 'student'),
    ('david.wilson', '$2a$10$DaViDwIlSoN135792468ZxCvBnMlKjIhGfEdCbAzYxWv', 'david.wilson@uni.edu', 'student'),
    ('jessica.davis', '$2a$10$JeSsIcAdAvIs246813579AbCdEfGhIjKlMnOpQrStUv', 'jessica.davis@uni.edu', 'student'),
    ('alex.martin', '$2a$10$AlExMaRtIn369258147ZyXwVuTsRqPoNmLkJiHgFeDc', 'alex.martin@uni.edu', 'student'),
    ('sophia.garcia', '$2a$10$SoPhIaGaRcIa147258369AbCdEfGhIjKlMnOpQrStUv', 'sophia.garcia@uni.edu', 'student'),
    ('james.taylor', '$2a$10$JaMeStAyLoR258369147ZxCvBnMlKjIhGfEdCbAzYx', 'james.taylor@uni.edu', 'student');
GO


INSERT INTO Meals (staffID, MealName, mealDescription, availableportion, expiryDate)
VALUES 
    (1, 'Vegetarian Pasta Bowl', 'Fresh pasta with seasonal vegetables and marinara sauce', 15, DATEADD(HOUR, 6, GETDATE())),
    (1, 'Grilled Chicken Sandwich', 'Whole grain bread with grilled chicken, lettuce, and tomato', 20, DATEADD(HOUR, 8, GETDATE())),
    (2, 'Caesar Salad', 'Romaine lettuce, parmesan, croutons, and caesar dressing', 12, DATEADD(HOUR, 4, GETDATE())),
    (2, 'Beef Burrito', 'Seasoned beef with rice, beans, cheese, and fresh salsa', 18, DATEADD(DAY, 1, GETDATE())),
    
    (3, 'Margherita Pizza Slices', 'Classic pizza with fresh mozzarella, basil, and tomato sauce', 25, DATEADD(DAY, 1, GETDATE())),
    (3, 'Thai Green Curry', 'Spicy green curry with vegetables and jasmine rice', 10, DATEADD(DAY, 2, GETDATE())),
    (4, 'Turkey Club Sandwich', 'Triple-decker with turkey, bacon, lettuce, and tomato', 14, DATEADD(DAY, 1, GETDATE())),
    (4, 'Mediterranean Wrap', 'Hummus, falafel, cucumber, tomato in a whole wheat wrap', 16, DATEADD(DAY, 2, GETDATE())),
    
    (1, 'Chicken Fried Rice', 'Asian-style fried rice with vegetables and chicken', 22, DATEADD(DAY, 3, GETDATE())),
    (2, 'Vegetable Soup & Bread', 'Hearty vegetable soup with fresh baked bread roll', 30, DATEADD(DAY, 4, GETDATE())),
    (3, 'BBQ Pulled Pork Sandwich', 'Slow-cooked pulled pork with coleslaw on a bun', 13, DATEADD(DAY, 3, GETDATE())),
    (4, 'Greek Salad Bowl', 'Mixed greens with feta, olives, cucumber, and tzatziki', 11, DATEADD(DAY, 5, GETDATE())),
    
    (1, 'Spaghetti Bolognese', 'Classic meat sauce with spaghetti', 0, DATEADD(DAY, 2, GETDATE())),
    (2, 'Fish Tacos', 'Grilled fish with cabbage slaw and lime crema', 0, DATEADD(DAY, 1, GETDATE())),
=
    (3, 'Quinoa Buddha Bowl', 'Quinoa with roasted vegetables, avocado, and tahini', 8, DATEADD(DAY, 2, GETDATE())),
    (4, 'Chicken Teriyaki', 'Grilled chicken with teriyaki glaze and steamed broccoli', 17, DATEADD(DAY, 3, GETDATE())),
    (1, 'Veggie Burger & Fries', 'Plant-based burger with sweet potato fries', 19, DATEADD(DAY, 1, GETDATE())),
    (2, 'Lasagna', 'Layered pasta with meat sauce and ricotta cheese', 24, DATEADD(DAY, 4, GETDATE()));
GO


INSERT INTO Claims (meal_id, student_id, claimed_at)
VALUES 
    (1, 5, DATEADD(HOUR, -2, GETDATE())),
    (1, 6, DATEADD(HOUR, -1, GETDATE())),
    (2, 7, DATEADD(HOUR, -3, GETDATE())),
    (3, 8, DATEADD(HOUR, -1, GETDATE())),
    (4, 9, DATEADD(MINUTE, -30, GETDATE())),
    
    (5, 5, DATEADD(DAY, -1, GETDATE())),
    (5, 10, DATEADD(DAY, -1, DATEADD(HOUR, -2, GETDATE()))),
    (6, 11, DATEADD(DAY, -1, DATEADD(HOUR, -4, GETDATE()))),
    (7, 12, DATEADD(DAY, -1, DATEADD(HOUR, -3, GETDATE()))),
    (8, 5, DATEADD(DAY, -1, DATEADD(HOUR, -1, GETDATE()))),
    
    (9, 6, DATEADD(DAY, -2, GETDATE())),
    (9, 7, DATEADD(DAY, -2, DATEADD(HOUR, -1, GETDATE()))),
    (10, 8, DATEADD(DAY, -2, DATEADD(HOUR, -2, GETDATE()))),
    (11, 9, DATEADD(DAY, -2, DATEADD(HOUR, -3, GETDATE()))),
    
    (13, 10, DATEADD(DAY, -1, DATEADD(HOUR, -5, GETDATE()))),
    (13, 11, DATEADD(DAY, -1, DATEADD(HOUR, -4, GETDATE()))),
    (13, 12, DATEADD(DAY, -1, DATEADD(HOUR, -3, GETDATE()))),
    (14, 5, DATEADD(DAY, -1, DATEADD(HOUR, -2, GETDATE()))),
    (14, 6, DATEADD(DAY, -1, DATEADD(HOUR, -1, GETDATE()))),
    
    (15, 7, DATEADD(HOUR, -4, GETDATE())),
    (16, 8, DATEADD(HOUR, -3, GETDATE())),
    (17, 9, DATEADD(HOUR, -2, GETDATE())),
    (18, 10, DATEADD(HOUR, -1, GETDATE())),
    (2, 11, DATEADD(MINUTE, -45, GETDATE())),
    (4, 12, DATEADD(MINUTE, -20, GETDATE()));
GO

-- =============================================
-- Verification Queries
-- =============================================

-- Display summary of inserted data
PRINT 'Data insertion complete!';
PRINT '';
PRINT 'Summary:';

SELECT 'Total Users' AS Category, COUNT(*) AS Count FROM Users
UNION ALL
SELECT 'Staff Members', COUNT(*) FROM Users WHERE UserRole = 'staff'
UNION ALL
SELECT 'Students', COUNT(*) FROM Users WHERE UserRole = 'student'
UNION ALL
SELECT 'Total Meals', COUNT(*) FROM Meals
UNION ALL
SELECT 'Available Meals', COUNT(*) FROM Meals WHERE availableportion > 0
UNION ALL
SELECT 'Total Claims', COUNT(*) FROM Claims;
GO
