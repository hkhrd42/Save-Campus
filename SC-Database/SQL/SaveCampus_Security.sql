USE SaveCampus;
GO

-- Security configuration: roles, permissions, and row-level security

-- Create roles for staff and students
IF DATABASE_PRINCIPAL_ID('staffRole') IS NULL
    CREATE ROLE staffRole;
GO

IF DATABASE_PRINCIPAL_ID('studentRole') IS NULL
    CREATE ROLE studentRole;
GO

-- Staff permissions (can manage meals and view claims)
GRANT SELECT, INSERT, UPDATE, DELETE ON Meals TO staffRole;
GRANT SELECT ON Claims TO staffRole;
GRANT SELECT, INSERT, UPDATE, DELETE ON Users TO staffRole;

-- Staff procedures access
GRANT EXECUTE ON AddMeal TO staffRole;
GRANT EXECUTE ON GetUserClaimHistory TO staffRole;

-- Staff functions access
GRANT EXECUTE ON CountMealClaims TO staffRole;
GRANT EXECUTE ON GetStaffMealCount TO staffRole;
GRANT EXECUTE ON GetTotalAvailablePortions TO staffRole;

-- Staff view access
GRANT SELECT ON vw_ActiveMeals TO staffRole;
GRANT SELECT ON ExpiredMeals TO staffRole;
GO

-- Student permissions (read-only meals, can claim)
GRANT SELECT ON Meals TO studentRole;
GRANT SELECT ON Users TO studentRole;

-- Students can claim meals
GRANT SELECT, INSERT ON Claims TO studentRole;
REVOKE INSERT, UPDATE, DELETE ON Meals FROM studentRole;

-- Student procedures access
GRANT EXECUTE ON ClaimMeal TO studentRole;
GRANT EXECUTE ON GetUserClaimHistory TO studentRole;

-- Student functions access
GRANT EXECUTE ON CountMealClaims TO studentRole;
GRANT EXECUTE ON GetTotalAvailablePortions TO studentRole;

-- Student view access
GRANT SELECT ON vw_ActiveMeals TO studentRole;
GO

-- Row-level security predicates (optional, commented out by default)
IF OBJECT_ID('Security.fn_MealSecurityPredicate', 'IF') IS NOT NULL
    DROP FUNCTION Security.fn_MealSecurityPredicate;
GO

CREATE SCHEMA Security;
GO

CREATE FUNCTION Security.fn_MealSecurityPredicate(@staffID INT)
RETURNS TABLE
WITH SCHEMABINDING
AS
RETURN (
    SELECT 1 AS fn_securitypredicate_result
    WHERE @staffID = CAST(SESSION_CONTEXT(N'user_id') AS INT)
        OR IS_MEMBER('staffRole') = 0  -- Allow if not staff (e.g., student viewing)
        OR IS_ROLEMEMBER('db_owner') = 1  -- Allow db owners
);
GO

-- Create security policy function for claims (students can only see their own claims)
IF OBJECT_ID('Security.fn_ClaimSecurityPredicate', 'IF') IS NOT NULL
    DROP FUNCTION Security.fn_ClaimSecurityPredicate;
GO

CREATE FUNCTION Security.fn_ClaimSecurityPredicate(@studentID INT)
RETURNS TABLE
WITH SCHEMABINDING
AS
RETURN (
    SELECT 1 AS fn_securitypredicate_result
    WHERE @studentID = CAST(SESSION_CONTEXT(N'user_id') AS INT)
        OR IS_ROLEMEMBER('staffRole') = 1  -- Allow staff to view all claims
        OR IS_ROLEMEMBER('db_owner') = 1  -- Allow db owners
);
GO

-- Note: To enable Row-Level Security policies, uncomment the following:
/*
-- Create security policy for Meals
IF EXISTS (SELECT * FROM sys.security_policies WHERE name = 'MealSecurityPolicy')
    DROP SECURITY POLICY Security.MealSecurityPolicy;
GO

CREATE SECURITY POLICY Security.MealSecurityPolicy
ADD FILTER PREDICATE Security.fn_MealSecurityPredicate(staffID)
ON dbo.Meals,
ADD BLOCK PREDICATE Security.fn_MealSecurityPredicate(staffID)
ON dbo.Meals AFTER INSERT,
ADD BLOCK PREDICATE Security.fn_MealSecurityPredicate(staffID)
ON dbo.Meals AFTER UPDATE
WITH (STATE = ON);
GO

-- Create security policy for Claims
IF EXISTS (SELECT * FROM sys.security_policies WHERE name = 'ClaimSecurityPolicy')
    DROP SECURITY POLICY Security.ClaimSecurityPolicy;
GO

CREATE SECURITY POLICY Security.ClaimSecurityPolicy
ADD FILTER PREDICATE Security.fn_ClaimSecurityPredicate(studentID)
ON dbo.Claims
WITH (STATE = ON);
GO
*/

-- =============================================
-- STORED PROCEDURE FOR USER SESSION CONTEXT
-- =============================================

-- Set user context for row-level security
IF OBJECT_ID('SetUserContext', 'P') IS NOT NULL
    DROP PROCEDURE SetUserContext;
GO

CREATE PROCEDURE SetUserContext
    @user_id INT
AS
BEGIN
    EXEC sp_set_session_context @key = N'user_id', @value = @user_id;
    PRINT 'User context set for UserID: ' + CAST(@user_id AS NVARCHAR);
END;
GO

-- Encryption examples (commented out by default)
-- Uncomment to enable encryption for sensitive data
-- IF NOT EXISTS (SELECT * FROM sys.symmetric_keys WHERE name = '##MS_DatabaseMasterKey##')
-- CREATE MASTER KEY ENCRYPTION BY PASSWORD = 'StrongPassword123!';
-- GO

-- Example: Create certificate for encryption
-- IF NOT EXISTS (SELECT * FROM sys.certificates WHERE name = 'SaveCampusCert')
-- CREATE CERTIFICATE SaveCampusCert
-- WITH SUBJECT = 'SaveCampus Data Protection';
-- GO

-- Example: Create symmetric key for encrypting sensitive data
-- IF NOT EXISTS (SELECT * FROM sys.symmetric_keys WHERE name = 'SaveCampusKey')
-- CREATE SYMMETRIC KEY SaveCampusKey
-- WITH ALGORITHM = AES_256
-- ENCRYPTION BY CERTIFICATE SaveCampusCert;
-- GO

PRINT 'Security roles and permissions configured';
GO
