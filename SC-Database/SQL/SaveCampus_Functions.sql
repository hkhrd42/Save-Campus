USE SaveCampus;
GO

-- Helper functions for meal and claim statistics

-- CountMealClaims: returns how many students claimed a specific meal
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

-- GetStaffMealCount: counts total meals posted by a staff member
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

-- GetTotalAvailablePortions: sum of all available portions across non-expired meals
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

-- HasUserClaimedMeal: checks if a student already claimed a meal (returns table)
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

PRINT 'Functions created';
GO
