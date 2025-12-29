USE SaveCampus;
GO

-- Cursor examples for batch processing and reporting

-- ExpireOldMeals: finds and marks expired meals
IF OBJECT_ID('ExpireOldMeals', 'P') IS NOT NULL
    DROP PROCEDURE ExpireOldMeals;
GO

CREATE PROCEDURE ExpireOldMeals
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @meal_id INT;
    DECLARE @meal_name NVARCHAR(100);
    DECLARE @available_portions INT;
    DECLARE @expired_count INT = 0;
    DECLARE @wasted_portions INT = 0;
    
    -- Declare cursor for expired meals
    DECLARE meal_cursor CURSOR FOR
    SELECT MealID, MealName, availableportion
    FROM Meals
    WHERE expiryDate < GETDATE() AND availableportion > 0;
    
    -- Open cursor
    OPEN meal_cursor;
    
    -- Fetch first row
    FETCH NEXT FROM meal_cursor INTO @meal_id, @meal_name, @available_portions;
    
    -- Loop through all expired meals
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Log expired meal
        PRINT 'Expiring meal: ' + @meal_name + ' (ID: ' + CAST(@meal_id AS NVARCHAR) + 
              ') - Wasted portions: ' + CAST(@available_portions AS NVARCHAR);
        
        -- Update statistics
        SET @expired_count = @expired_count + 1;
        SET @wasted_portions = @wasted_portions + @available_portions;
        
        -- Set available portions to 0 for expired meals
        UPDATE Meals
        SET availableportion = 0,
            UpdatedAt = GETDATE()
        WHERE MealID = @meal_id;
        
        -- Fetch next row
        FETCH NEXT FROM meal_cursor INTO @meal_id, @meal_name, @available_portions;
    END
    
    -- Close and deallocate cursor
    CLOSE meal_cursor;
    DEALLOCATE meal_cursor;
    
    -- Print summary
    PRINT '';
    PRINT 'Summary:';
    PRINT 'Total expired meals: ' + CAST(@expired_count AS NVARCHAR);
    PRINT 'Total wasted portions: ' + CAST(@wasted_portions AS NVARCHAR);
END;
GO

-- GenerateStudentClaimReport: prints claim statistics for each student
IF OBJECT_ID('GenerateStudentClaimReport', 'P') IS NOT NULL
    DROP PROCEDURE GenerateStudentClaimReport;
GO

CREATE PROCEDURE GenerateStudentClaimReport
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @student_id INT;
    DECLARE @student_name NVARCHAR(50);
    DECLARE @student_email NVARCHAR(100);
    DECLARE @claim_count INT;
    
    PRINT '========================================';
    PRINT 'STUDENT CLAIM REPORT';
    PRINT '========================================';
    PRINT '';
    
    -- Declare cursor for all students
    DECLARE student_cursor CURSOR FOR
    SELECT UserID, Username, Email
    FROM Users
    WHERE UserRole = 'student'
    ORDER BY Username;
    
    -- Open cursor
    OPEN student_cursor;
    
    -- Fetch first student
    FETCH NEXT FROM student_cursor INTO @student_id, @student_name, @student_email;
    
    -- Loop through all students
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Count claims for this student
        SELECT @claim_count = COUNT(*)
        FROM Claims
        WHERE studentID = @student_id;
        
        -- Print student info
        PRINT 'Student: ' + @student_name;
        PRINT 'Email: ' + @student_email;
        PRINT 'Total Claims: ' + CAST(@claim_count AS NVARCHAR);
        PRINT '----------------------------------------';
        
        -- Fetch next student
        FETCH NEXT FROM student_cursor INTO @student_id, @student_name, @student_email;
    END
    
    -- Close and deallocate cursor
    CLOSE student_cursor;
    DEALLOCATE student_cursor;
    
    PRINT '';
    PRINT 'Report generated successfully!';
END;
GO

-- UpdateMealStatistics: shows claim stats for all meals
IF OBJECT_ID('UpdateMealStatistics', 'P') IS NOT NULL
    DROP PROCEDURE UpdateMealStatistics;
GO

CREATE PROCEDURE UpdateMealStatistics
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @meal_id INT;
    DECLARE @meal_name NVARCHAR(100);
    DECLARE @claim_count INT;
    DECLARE @staff_name NVARCHAR(50);
    
    PRINT '========================================';
    PRINT 'MEAL STATISTICS REPORT';
    PRINT '========================================';
    PRINT '';
    
    -- Declare cursor for all meals
    DECLARE stats_cursor CURSOR FOR
    SELECT m.MealID, m.MealName, u.Username
    FROM Meals m, Users u
    WHERE m.staffID = u.UserID
    ORDER BY m.CreatedAt DESC;
    
    -- Open cursor
    OPEN stats_cursor;
    
    -- Fetch first meal
    FETCH NEXT FROM stats_cursor INTO @meal_id, @meal_name, @staff_name;
    
    -- Loop through all meals
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Get claim count using our function
        SET @claim_count = dbo.CountMealClaims(@meal_id);
        
        -- Print meal statistics
        PRINT 'Meal: ' + @meal_name + ' (ID: ' + CAST(@meal_id AS NVARCHAR) + ')';
        PRINT 'Posted by: ' + @staff_name;
        PRINT 'Total Claims: ' + CAST(@claim_count AS NVARCHAR);
        PRINT '----------------------------------------';
        
        -- Fetch next meal
        FETCH NEXT FROM stats_cursor INTO @meal_id, @meal_name, @staff_name;
    END
    
    -- Close and deallocate cursor
    CLOSE stats_cursor;
    DEALLOCATE stats_cursor;
    
    PRINT '';
    PRINT 'Statistics report generated successfully!';
END;
GO

-- ExtendMealExpiryDates: adds hours to meals expiring soon
IF OBJECT_ID('ExtendMealExpiryDates', 'P') IS NOT NULL
    DROP PROCEDURE ExtendMealExpiryDates;
GO

CREATE PROCEDURE ExtendMealExpiryDates
    @hours_to_add INT
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @meal_id INT;
    DECLARE @meal_name NVARCHAR(100);
    DECLARE @old_expiry DATETIME;
    DECLARE @new_expiry DATETIME;
    DECLARE @updated_count INT = 0;
    
    PRINT '========================================';
    PRINT 'EXTENDING MEAL EXPIRY DATES';
    PRINT 'Adding ' + CAST(@hours_to_add AS NVARCHAR) + ' hours';
    PRINT '========================================';
    PRINT '';
    
    -- Declare cursor for meals expiring soon
    DECLARE expiry_cursor CURSOR FOR
    SELECT MealID, MealName, expiryDate
    FROM Meals
    WHERE expiryDate > GETDATE()
        AND expiryDate < DATEADD(DAY, 2, GETDATE())
        AND availableportion > 0;
    
    -- Open cursor
    OPEN expiry_cursor;
    
    -- Fetch first meal
    FETCH NEXT FROM expiry_cursor INTO @meal_id, @meal_name, @old_expiry;
    
    -- Loop through meals
    WHILE @@FETCH_STATUS = 0
    BEGIN
        SET @new_expiry = DATEADD(HOUR, @hours_to_add, @old_expiry);
        
        -- Update expiry date
        UPDATE Meals
        SET expiryDate = @new_expiry,
            UpdatedAt = GETDATE()
        WHERE MealID = @meal_id;
        
        -- Log the update
        PRINT 'Updated: ' + @meal_name;
        PRINT '  Old expiry: ' + CONVERT(NVARCHAR, @old_expiry, 120);
        PRINT '  New expiry: ' + CONVERT(NVARCHAR, @new_expiry, 120);
        PRINT '----------------------------------------';
        
        SET @updated_count = @updated_count + 1;
        
        -- Fetch next meal
        FETCH NEXT FROM expiry_cursor INTO @meal_id, @meal_name, @old_expiry;
    END
    
    -- Close and deallocate cursor
    CLOSE expiry_cursor;
    DEALLOCATE expiry_cursor;
    
    PRINT '';
    PRINT 'Total meals updated: ' + CAST(@updated_count AS NVARCHAR);
END;
GO

-- GenerateStaffPerformanceReport: detailed stats per staff member
IF OBJECT_ID('GenerateStaffPerformanceReport', 'P') IS NOT NULL
    DROP PROCEDURE GenerateStaffPerformanceReport;
GO

CREATE PROCEDURE GenerateStaffPerformanceReport
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @staff_id INT;
    DECLARE @staff_name NVARCHAR(50);
    DECLARE @staff_email NVARCHAR(100);
    DECLARE @meal_count INT;
    DECLARE @total_claims INT;
    DECLARE @total_portions INT;
    
    PRINT '========================================';
    PRINT 'STAFF PERFORMANCE REPORT';
    PRINT '========================================';
    PRINT '';
    
    -- Declare cursor for all staff members
    DECLARE staff_cursor CURSOR FOR
    SELECT UserID, Username, Email
    FROM Users
    WHERE UserRole = 'staff'
    ORDER BY Username;
    
    -- Open cursor
    OPEN staff_cursor;
    
    -- Fetch first staff member
    FETCH NEXT FROM staff_cursor INTO @staff_id, @staff_name, @staff_email;
    
    -- Loop through all staff
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Get statistics for this staff member
        SELECT @meal_count = COUNT(*)
        FROM Meals
        WHERE staffID = @staff_id;
        
        SELECT @total_portions = ISNULL(SUM(availableportion), 0)
        FROM Meals
        WHERE staffID = @staff_id;
        
        SELECT @total_claims = COUNT(*)
        FROM Claims c, Meals m
        WHERE c.foodID = m.MealID
            AND m.staffID = @staff_id;
        
        -- Print staff performance
        PRINT 'Staff: ' + @staff_name;
        PRINT 'Email: ' + @staff_email;
        PRINT 'Total Meals Posted: ' + CAST(@meal_count AS NVARCHAR);
        PRINT 'Total Portions Offered: ' + CAST(@total_portions AS NVARCHAR);
        PRINT 'Total Claims Received: ' + CAST(@total_claims AS NVARCHAR);
        
        IF @meal_count > 0
        BEGIN
            DECLARE @avg_claims DECIMAL(10,2) = CAST(@total_claims AS DECIMAL) / @meal_count;
            PRINT 'Average Claims per Meal: ' + CAST(@avg_claims AS NVARCHAR);
        END
        
        PRINT '========================================';
        PRINT '';
        
        -- Fetch next staff member
        FETCH NEXT FROM staff_cursor INTO @staff_id, @staff_name, @staff_email;
    END
    
    -- Close and deallocate cursor
    CLOSE staff_cursor;
    DEALLOCATE staff_cursor;
    
    PRINT 'Performance report generated successfully!';
END;
GO

PRINT 'Cursor procedures created';
GO
