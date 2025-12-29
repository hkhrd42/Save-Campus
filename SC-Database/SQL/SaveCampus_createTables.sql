USE SaveCampus;
GO

CREATE TABLE Users (
    UserID INT PRIMARY KEY IDENTITY(1,1),
    Username NVARCHAR(50) NOT NULL,
    PasswordHash NVARCHAR(255) NOT NULL,
    Email NVARCHAR(100) NOT NULL,
    UserRole NVARCHAR(50) NOT NULL CHECK (UserRole IN ('staff','student')),
    CreatedAt DATETIME DEFAULT GETDATE(),
    UpdatedAt DATETIME DEFAULT GETDATE()
);
GO

CREATE TABLE Meals (
    MealID INT PRIMARY KEY IDENTITY(1,1),
    staffID INT NOT NULL,
    MealName NVARCHAR(100) NOT NULL,
    mealDescription NVARCHAR(255),
    availableportion INT NOT NULL CHECK (availableportion >= 0),
    expiryDate DATETIME NOT NULL,
    CreatedAt DATETIME DEFAULT GETDATE(),
    UpdatedAt DATETIME DEFAULT GETDATE()
    
    CONSTRAINT FK_Meal_Staff
        FOREIGN KEY (staffID)
        REFERENCES Users(UserID)
        ON DELETE CASCADE
);
GO

CREATE TABLE Claims (
    claimID INT IDENTITY(1,1) PRIMARY KEY,
    foodID INT NOT NULL,
    studentID INT NOT NULL,
    claimed_at DATETIME NOT NULL DEFAULT GETDATE(),

    CONSTRAINT FK_Claim_Meal
        FOREIGN KEY (foodID)
        REFERENCES Meals(MealID),

    CONSTRAINT FK_Claim_Student
        FOREIGN KEY (studentID)
        REFERENCES Users(UserID)
);
GO
