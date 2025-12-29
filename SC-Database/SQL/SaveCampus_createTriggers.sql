USE SaveCampus;
GO

-- Trigger to ensure UserRole is either 'staff' or 'student'
CREATE TRIGGER trg_User_Role_Check
ON Users
FOR INSERT, UPDATE
AS
BEGIN
    IF EXISTS (
        SELECET 1 FROM inserted
        WHERE UserRole NOT IN ('staff', 'student')
    )
    BEGIN
        RAISERROR('Invalid UserRole. Must be either "staff" or "student".', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;
GO

-- Trigger to ensure only 'staff' can insert meals
CREATE TRIGGER trg_Staffonly_Insert_Meal
ON Meals
FOR INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1 FROM inserted i, Users u
        WHERE i.staffID = u.UserID
            AND u.UserRole <> 'staff'
    )
    BEGIN
        RAISERROR('Only users with UserRole "staff" can insert meals.', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;
GO

-- Trigger to ensure only 'student' can claim meals
CREATE TRIGGER trg_Studentonly_Insert_Claim
ON Claims
FOR INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1 FROM inserted i, Users u
        WHERE i.student_id = u.UserID
            AND u.UserRole <> 'student'
    )
    BEGIN
        RAISERROR('Only users with UserRole "student" can claim meals.', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;
GO

-- Trigger to ensure cannot claim expired meals
CREATE TRIGGER trg_Claim_Not_Expired_Meal
ON Claims
FOR INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1 FROM inserted i, Meals m
        WHERE i.meal_id = m.MealID
            AND m.expiryDate < GETDATE()
    )
    BEGIN
        RAISERROR('Cannot claim expired meals.', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;
GO

--Trigger to not claim meal if no available portion
CREATE TRIGGER trg_Claim_Available_Portion
ON Claims
FOR INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1 FROM inserted i, Meals m
        WHERE i.meal_id = m.MealID
            AND m.availableportion <= 0
    )
    BEGIN
        RAISERROR('Cannot claim meal with no available portions.', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;
GO

-- Trigger to decrement availableportion on meal claim
CREATE TRIGGER trg_Decrement_Available_Portion
ON Claims
AFTER INSERT
AS
BEGIN
    UPDATE Meals
    SET availableportion = availableportion - 1
    WHERE MealID IN (
        SELECT meal_id FROM inserted
    );
END;
