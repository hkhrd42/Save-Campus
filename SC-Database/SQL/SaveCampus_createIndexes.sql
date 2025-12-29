USE SaveCampus;
GO

CREATE UNIQUE INDEX IDX_users_email ON Users(Email);
GO

CREATE INDEX IDX_meals_staffID ON Meals(staffID);
CREATE INDEX IDX_meals_expiryDate ON Meals(expiryDate);
GO

CREATE INDEX IDX_claims_foodID ON Claims(foodID);
CREATE INDEX IDX_claims_studentID ON Claims(studentID);
GO