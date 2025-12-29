USE SaveCampus;
GO

-- =============================================
-- STORED PROCEDURES
-- =============================================

-- Procedure 1: Claim a meal with transaction + locking
-- This ensures thread-safe claiming with proper concurrency control
IF OBJECT_ID('ClaimMeal', 'P') IS NOT NULL
    DROP PROCEDURE ClaimMeal;
GO

CREATE PROCEDURE ClaimMeal
    @meal_id INT,
    @user_id INT
AS
BEGIN
    SET NOCOUNT ON;
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    
    BEGIN TRANSACTION;
    
    BEGIN TRY
        DECLARE @available INT;
        DECLARE @userRole NVARCHAR(50);
        DECLARE @expiryDate DATETIME;
        
        -- Check if user exists and is a student
        SELECT @userRole = UserRole
        FROM Users
        WHERE UserID = @user_id;
        
        IF @userRole IS NULL
        BEGIN
            RAISERROR('User not found.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @userRole != 'student'
        BEGIN
            RAISERROR('Only students can claim meals.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        -- Lock the meal row and check availability
        SELECT @available = availableportion, @expiryDate = expiryDate
        FROM Meals WITH (UPDLOCK, ROWLOCK)
        WHERE MealID = @meal_id;
        
        IF @available IS NULL
        BEGIN
            RAISERROR('Meal not found.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @expiryDate < GETDATE()
        BEGIN
            RAISERROR('Meal has expired.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @available < 1
        BEGIN
            RAISERROR('No portions left.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        -- Check if student already claimed this meal
        IF EXISTS (SELECT 1 FROM Claims WHERE foodID = @meal_id AND studentID = @user_id)
        BEGIN
            RAISERROR('You have already claimed this meal.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        -- Insert claim
        INSERT INTO Claims (foodID, studentID, claimed_at)
        VALUES (@meal_id, @user_id, GETDATE());
        
        -- Update available portions
        UPDATE Meals
        SET availableportion = availableportion - 1,
            UpdatedAt = GETDATE()
        WHERE MealID = @meal_id;
        
        COMMIT TRANSACTION;
        
        PRINT 'Meal claimed successfully!';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;
        
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        DECLARE @ErrorSeverity INT = ERROR_SEVERITY();
        DECLARE @ErrorState INT = ERROR_STATE();
        
        RAISERROR(@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

-- Procedure 2: Add a new meal (for staff only)
IF OBJECT_ID('AddMeal', 'P') IS NOT NULL
    DROP PROCEDURE AddMeal;
GO

CREATE PROCEDURE AddMeal
    @staff_id INT,
    @meal_name NVARCHAR(100),
    @meal_description NVARCHAR(255),
    @available_portions INT,
    @expiry_date DATETIME
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRANSACTION;
    
    BEGIN TRY
        DECLARE @userRole NVARCHAR(50);
        
        -- Verify user is staff
        SELECT @userRole = UserRole
        FROM Users
        WHERE UserID = @staff_id;
        
        IF @userRole IS NULL
        BEGIN
            RAISERROR('User not found.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @userRole != 'staff'
        BEGIN
            RAISERROR('Only staff members can add meals.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @available_portions < 0
        BEGIN
            RAISERROR('Available portions must be non-negative.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @expiry_date <= GETDATE()
        BEGIN
            RAISERROR('Expiry date must be in the future.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        -- Insert the meal
        INSERT INTO Meals (staffID, MealName, mealDescription, availableportion, expiryDate)
        VALUES (@staff_id, @meal_name, @meal_description, @available_portions, @expiry_date);
        
        COMMIT TRANSACTION;
        
        PRINT 'Meal added successfully!';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;
        
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        DECLARE @ErrorSeverity INT = ERROR_SEVERITY();
        DECLARE @ErrorState INT = ERROR_STATE();
        
        RAISERROR(@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

-- Procedure 3: Get user claim history
IF OBJECT_ID('GetUserClaimHistory', 'P') IS NOT NULL
    DROP PROCEDURE GetUserClaimHistory;
GO

CREATE PROCEDURE GetUserClaimHistory
    @user_id INT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        c.claimID,
        m.MealName,
        m.mealDescription,
        c.claimed_at,
        m.expiryDate,
        u.Username AS staff_name
    FROM Claims c, Meals m, Users u
    WHERE c.foodID = m.MealID
        AND m.staffID = u.UserID
        AND c.studentID = @user_id
    ORDER BY c.claimed_at DESC;
END;
GO

-- =============================================
-- FUNCTIONS
-- =============================================

-- Function 1: Count claims for a specific meal
IF OBJECT_ID('CountMealClaims', 'FN') IS NOT NULL
    DROP FUNCTION CountMealClaims;
GO

CREATE FUNCTION CountMealClaims (@meal_id INT)
RETURNS INT
AS
BEGIN
    DECLARE @count INT;
    
    SELECT @count = COUNT(*)
    FROM Claims
    WHERE foodID = @meal_id;
    
    RETURN ISNULL(@count, 0);
END;
GO

-- Function 2: Get total meals posted by a staff member
IF OBJECT_ID('GetStaffMealCount', 'FN') IS NOT NULL
    DROP FUNCTION GetStaffMealCount;
GO

CREATE FUNCTION GetStaffMealCount (@staff_id INT)
RETURNS INT
AS
BEGIN
    DECLARE @count INT;
    
    SELECT @count = COUNT(*)
    FROM Meals
    WHERE staffID = @staff_id;
    
    RETURN ISNULL(@count, 0);
END;
GO

-- Function 3: Calculate total available portions across all meals
IF OBJECT_ID('GetTotalAvailablePortions', 'FN') IS NOT NULL
    DROP FUNCTION GetTotalAvailablePortions;
GO

CREATE FUNCTION GetTotalAvailablePortions ()
RETURNS INT
AS
BEGIN
    DECLARE @total INT;
    
    SELECT @total = SUM(availableportion)
    FROM Meals
    WHERE expiryDate > GETDATE();
    
    RETURN ISNULL(@total, 0);
END;
GO

-- Function 4: Check if user has claimed a specific meal (Table-Valued Function)
IF OBJECT_ID('HasUserClaimedMeal', 'IF') IS NOT NULL
    DROP FUNCTION HasUserClaimedMeal;
GO

CREATE FUNCTION HasUserClaimedMeal (@meal_id INT, @user_id INT)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        CASE WHEN EXISTS (
            SELECT 1 FROM Claims 
            WHERE foodID = @meal_id AND studentID = @user_id
        ) THEN 1 ELSE 0 END AS HasClaimed
);
GO

-- =============================================
-- TRIGGERS
-- =============================================

-- Trigger 1: Update UpdatedAt timestamp on Users table modification
IF OBJECT_ID('trg_Users_UpdateTimestamp', 'TR') IS NOT NULL
    DROP TRIGGER trg_Users_UpdateTimestamp;
GO

CREATE TRIGGER trg_Users_UpdateTimestamp
ON Users
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    
    UPDATE Users
    SET UpdatedAt = GETDATE()
    WHERE UserID IN (SELECT UserID FROM inserted);
END;
GO

-- Trigger 2: Update UpdatedAt timestamp on Meals table modification
IF OBJECT_ID('trg_Meals_UpdateTimestamp', 'TR') IS NOT NULL
    DROP TRIGGER trg_Meals_UpdateTimestamp;
GO

CREATE TRIGGER trg_Meals_UpdateTimestamp
ON Meals
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    
    UPDATE Meals
    SET UpdatedAt = GETDATE()
    WHERE MealID IN (SELECT MealID FROM inserted);
END;
GO

-- Trigger 3: Prevent deletion of meals with claims
IF OBJECT_ID('trg_Meals_PreventDeleteWithClaims', 'TR') IS NOT NULL
    DROP TRIGGER trg_Meals_PreventDeleteWithClaims;
GO

CREATE TRIGGER trg_Meals_PreventDeleteWithClaims
ON Meals
INSTEAD OF DELETE
AS
BEGIN
    SET NOCOUNT ON;
    
    IF EXISTS (
        SELECT 1 
        FROM deleted d, Claims c
        WHERE d.MealID = c.foodID
    )
    BEGIN
        RAISERROR('Cannot delete meals that have been claimed. Remove claims first.', 16, 1);
        ROLLBACK TRANSACTION;
        RETURN;
    END
    
    -- If no claims exist, allow deletion
    DELETE FROM Meals
    WHERE MealID IN (SELECT MealID FROM deleted);
END;
GO

-- Trigger 4: Log meal claims (audit trigger)
-- First create an audit table
IF OBJECT_ID('ClaimAudit', 'U') IS NOT NULL
    DROP TABLE ClaimAudit;
GO

CREATE TABLE ClaimAudit (
    AuditID INT IDENTITY(1,1) PRIMARY KEY,
    claimID INT,
    foodID INT,
    studentID INT,
    action_type NVARCHAR(10),
    action_date DATETIME DEFAULT GETDATE()
);
GO

IF OBJECT_ID('trg_Claims_AuditLog', 'TR') IS NOT NULL
    DROP TRIGGER trg_Claims_AuditLog;
GO

CREATE TRIGGER trg_Claims_AuditLog
ON Claims
AFTER INSERT, DELETE
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Log insertions
    INSERT INTO ClaimAudit (claimID, foodID, studentID, action_type)
    SELECT claimID, foodID, studentID, 'INSERT'
    FROM inserted;
    
    -- Log deletions
    INSERT INTO ClaimAudit (claimID, foodID, studentID, action_type)
    SELECT claimID, foodID, studentID, 'DELETE'
    FROM deleted;
END;
GO

-- =============================================
-- CURSOR EXAMPLES
-- =============================================

-- Cursor Example 1: Expire old meals and notify
-- This cursor iterates through expired meals and marks them
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

-- Cursor Example 2: Generate student claim report
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

-- Cursor Example 3: Update meal statistics using cursor
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

-- =============================================
-- USAGE EXAMPLES
-- =============================================

PRINT '========================================';
PRINT 'SaveCampus Advanced Services Created!';
PRINT '========================================';
PRINT '';
PRINT 'STORED PROCEDURES:';
PRINT '  - ClaimMeal: Claim a meal with transaction + locking';
PRINT '  - AddMeal: Add a new meal (staff only)';
PRINT '  - GetUserClaimHistory: Get claim history for a user';
PRINT '  - ExpireOldMeals: Expire old meals using cursor';
PRINT '  - GenerateStudentClaimReport: Generate report using cursor';
PRINT '  - UpdateMealStatistics: Update statistics using cursor';
PRINT '';
PRINT 'FUNCTIONS:';
PRINT '  - CountMealClaims: Count claims for a meal';
PRINT '  - GetStaffMealCount: Count meals by staff member';
PRINT '  - GetTotalAvailablePortions: Total available portions';
PRINT '  - HasUserClaimedMeal: Check if user claimed a meal (TVF)';
PRINT '';
PRINT 'TRIGGERS:';
PRINT '  - trg_Users_UpdateTimestamp: Auto-update timestamp';
PRINT '  - trg_Meals_UpdateTimestamp: Auto-update timestamp';
PRINT '  - trg_Meals_PreventDeleteWithClaims: Prevent deletion';
PRINT '  - trg_Claims_AuditLog: Audit claim actions';
PRINT '';
PRINT 'Example Usage:';
PRINT '  EXEC ClaimMeal @meal_id = 1, @user_id = 5;';
PRINT '  EXEC GetUserClaimHistory @user_id = 5;';
PRINT '  SELECT dbo.CountMealClaims(1);';
PRINT '  EXEC ExpireOldMeals;';
PRINT '  EXEC GenerateStudentClaimReport;';
GO
