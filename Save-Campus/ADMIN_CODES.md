# Admin Registration Codes

## Default Staff Registration Codes

The following admin codes are pre-configured in the database for staff registration:

1. **STAFF2025** - General staff registration code
2. **ADMIN2025** - Admin registration code
3. **CAMPUS2025** - Campus staff registration code

## How Staff Registration Works

1. **Student Registration**: 
   - Students can register freely without any code
   - Select "Student" option during registration
   - No admin code required

2. **Staff Registration**:
   - Select "Staff (Admin)" option during registration
   - Enter one of the admin codes listed above
   - Each code can only be used once
   - After successful registration, the code is marked as used

## Registration Process

### For Students:
1. Go to registration page
2. Fill in Name, Email, and Password
3. Select "Student" as role
4. Click Register

### For Staff:
1. Go to registration page
2. Fill in Name, Email, and Password
3. Select "Staff (Admin)" as role
4. Enter a valid admin code (e.g., STAFF2025)
5. Click Register

## Design Theme

The entire application now uses a **white and green** color scheme:

- **Primary Colors**: Green (#16a34a) and Emerald (#059669)
- **Background**: Clean white with subtle green gradients
- **Buttons**: Green gradient buttons with hover effects
- **Navigation**: White background with green accents
- **Footer**: Green gradient with white text

## Database Structure

The system uses an `admin_codes` table with the following structure:

```
admin_codes
- id (primary key)
- code (unique string)
- is_used (boolean, default: false)
- used_by (foreign key to users.id)
- created_at
- updated_at
```

## Adding New Admin Codes

To add more admin codes to the database, run this SQL command:

```sql
INSERT INTO admin_codes (code, is_used, created_at, updated_at) 
VALUES ('YOUR_NEW_CODE', false, NOW(), NOW());
```

Or use Laravel tinker:

```bash
php artisan tinker
DB::table('admin_codes')->insert(['code' => 'YOUR_NEW_CODE', 'created_at' => now(), 'updated_at' => now()]);
```

## Security Notes

- Admin codes are single-use only
- Used codes cannot be reused
- Codes are case-sensitive
- Staff members have access to additional features for posting meals
- Students can only claim meals
