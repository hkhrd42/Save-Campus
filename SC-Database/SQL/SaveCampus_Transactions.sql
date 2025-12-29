USE SaveCampus;
GO

-- Transaction examples demonstrating ACID properties and error handling

-- Example 1: Transfer meal ownership between staff members
PRINT '-- Example: Transfer Meal Ownership --';
GO

BEGIN TRANSACTION;

BEGIN TRY
    DECLARE @source_staff_id INT = 1;
    DECLARE @target_staff_id INT = 2;
    DECLARE @meal_id INT = 1;
    
    -- Verify source staff exists
    IF NOT EXISTS (SELECT 1 FROM Users WHERE UserID = @source_staff_id AND UserRole = 'staff')
    BEGIN
        RAISERROR('Source staff member not found.', 16, 1);
    END
    
    -- Verify target staff exists
    IF NOT EXISTS (SELECT 1 FROM Users WHERE UserID = @target_staff_id AND UserRole = 'staff')
    BEGIN
        RAISERROR('Target staff member not found.', 16, 1);
    END
    
    -- Verify meal belongs to source staff
    IF NOT EXISTS (SELECT 1 FROM Meals WHERE MealID = @meal_id AND staffID = @source_staff_id)
    BEGIN
        RAISERROR('Meal not found or does not belong to source staff.', 16, 1);
    END
    
    -- Transfer ownership
    UPDATE Meals
    SET staffID = @target_staff_id,
        UpdatedAt = GETDATE()
    WHERE MealID = @meal_id;
    
    COMMIT TRANSACTION;
    PRINT 'Meal ownership transferred successfully!';
END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0
        ROLLBACK TRANSACTION;
    
    PRINT 'Error: ' + ERROR_MESSAGE();
END CATCH;
GO

-- Example 2: Batch claim multiple meals at once
PRINT '';
PRINT '-- Example: Batch Claim Meals --';
GO

CREATE PROCEDURE BatchClaimMeals
    @user_id INT,
    @meal_ids NVARCHAR(MAX)  -- Comma-separated list of meal IDs
AS
BEGIN
    SET NOCOUNT ON;
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    
    BEGIN TRANSACTION;
    
    BEGIN TRY
        DECLARE @userRole NVARCHAR(50);
        DECLARE @claims_made INT = 0;
        
        -- Check if user is a student
        SELECT @userRole = UserRole
        FROM Users
        WHERE UserID = @user_id;
        
        IF @userRole IS NULL OR @userRole != 'student'
        BEGIN
            RAISERROR('User must be a student to claim meals.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        -- Create temp table for meal IDs
        CREATE TABLE #MealsToClaim (MealID INT);
        
        -- Parse comma-separated meal IDs (simplified - in production use STRING_SPLIT)
        INSERT INTO #MealsToClaim (MealID)
        SELECT CAST(value AS INT)
        FROM STRING_SPLIT(@meal_ids, ',');
        
        -- Validate and claim each meal
        DECLARE @current_meal_id INT;
        DECLARE @available INT;
        DECLARE @expiry_date DATETIME;
        
        DECLARE meal_cursor CURSOR FOR
        SELECT MealID FROM #MealsToClaim;
        
        OPEN meal_cursor;
        FETCH NEXT FROM meal_cursor INTO @current_meal_id;
        
        WHILE @@FETCH_STATUS = 0
        BEGIN
            -- Check availability
            SELECT @available = availableportion, @expiry_date = expiryDate
            FROM Meals WITH (UPDLOCK, ROWLOCK)
            WHERE MealID = @current_meal_id;
            
            IF @available > 0 AND @expiry_date > GETDATE()
            BEGIN
                -- Check if not already claimed
                IF NOT EXISTS (SELECT 1 FROM Claims WHERE foodID = @current_meal_id AND studentID = @user_id)
                BEGIN
                    -- Claim the meal
                    INSERT INTO Claims (foodID, studentID, claimed_at)
                    VALUES (@current_meal_id, @user_id, GETDATE());
                    
                    -- Update available portions
                    UPDATE Meals
                    SET availableportion = availableportion - 1
                    WHERE MealID = @current_meal_id;
                    
                    SET @claims_made = @claims_made + 1;
                END
            END
            
            FETCH NEXT FROM meal_cursor INTO @current_meal_id;
        END
        
        CLOSE meal_cursor;
        DEALLOCATE meal_cursor;
        
        DROP TABLE #MealsToClaim;
        
        COMMIT TRANSACTION;
        
        PRINT 'Successfully claimed ' + CAST(@claims_made AS NVARCHAR) + ' meals.';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;
        
        IF CURSOR_STATUS('local', 'meal_cursor') >= 0
        BEGIN
            CLOSE meal_cursor;
            DEALLOCATE meal_cursor;
        END
        
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@ErrorMessage, 16, 1);
    END CATCH
END;
GO

-- Example 3: Delete user with cascading cleanup
PRINT '';
PRINT '-- Example: Delete User with Cleanup --';
GO

CREATE PROCEDURE DeleteUserWithCleanup
    @user_id INT
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRANSACTION;
    
    BEGIN TRY
        DECLARE @userRole NVARCHAR(50);
        
        -- Check if user exists
        SELECT @userRole = UserRole
        FROM Users
        WHERE UserID = @user_id;
        
        IF @userRole IS NULL
        BEGIN
            RAISERROR('User not found.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        
        IF @userRole = 'student'
        BEGIN
            -- Delete student's claims
            DELETE FROM Claims
            WHERE studentID = @user_id;
            
            PRINT 'Deleted student claims.';
        END
        ELSE IF @userRole = 'staff'
        BEGIN
            -- Check if staff has meals with claims
            IF EXISTS (
                SELECT 1 
                FROM Meals m, Claims c
                WHERE m.staffID = @user_id 
                    AND c.foodID = m.MealID
            )
            BEGIN
                RAISERROR('Cannot delete staff member with claimed meals. Reassign meals first.', 16, 1);
                ROLLBACK TRANSACTION;
                RETURN;
            END
            
            -- Delete staff's meals (will cascade due to FK)
            DELETE FROM Meals
            WHERE staffID = @user_id;
            
            PRINT 'Deleted staff meals.';
        END
        
        -- Delete the user
        DELETE FROM Users
        WHERE UserID = @user_id;
        
        COMMIT TRANSACTION;
        
        PRINT 'User deleted successfully!';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;
        
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@ErrorMessage, 16, 1);
    END CATCH
END;
GO

PRINT '';
PRINT 'Transaction procedures created';
GO
