USE SaveCampus;
GO


CREATE ROLE staffRole;
CREATE ROLE studentRole;
GO


GRANT SELECT, INSERT, UPDATE, DELETE ON Users TO staffRole;
REVOKE INSERT ON Claims FROM staffRole;
REVOKE SELECT ON StudentClaimHistory FROM staffRole;
GO


GRANT SELECT ON ActiveMeals TO studentRole;
GRANT INSERT ON Claims TO studentRole;
GRANT SELECT ON StudentClaimHistory TO studentRole;
REVOKE INSERT, UPDATE, DELETE ON Meals FROM studentRole;
REVOKE SELECT ON StaffMealStats FROM studentRole;
GO
