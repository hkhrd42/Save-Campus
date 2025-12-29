USE SaveCampus;
GO

-- Stored procedures for SaveCampus meal claiming system

-- ClaimMeal: handles meal claiming with proper locking to prevent race conditions
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

-- AddMeal: allows staff to post new meals
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

-- GetUserClaimHistory: retrieves all meals claimed by a student
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

PRINT 'Procedures created successfully';
PRINT 'Available: ClaimMeal, AddMeal, GetUserClaimHistory';
GO
