# Admin User Seeder Documentation

This Laravel project includes several seeders for creating admin users with the proper roles and permissions.

## Available Seeders

### 1. AdminUserSeeder
Creates multiple admin users with full permissions.

**Usage:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

**Created Users:**
- **Super Admin**: superadmin@gmail.com / SuperAdmin123!
- **Manager**: manager@gmail.com / Manager123!
- **Support**: support@gmail.com / Support123!

### 2. CreateSingleAdminSeeder
Interactive seeder that allows you to create a single admin user with custom details.

**Usage:**
```bash
php artisan db:seed --class=CreateSingleAdminSeeder
```

This seeder will prompt you for:
- Admin name
- Email address
- Password (or use default)
- Phone number
- Country

### 3. Existing Seeders
- **StationSeeder**: Creates the original admin user (admin@gmail.com / WowsomeRyt2025)
- **ResetStationsAndAdminSeeder**: Updates the original admin password

## Admin System Overview

### Roles & Permissions
- **Role**: `admin` - Required for admin access
- **Permissions**: 
  - `full` - Full admin access
  - `view` - View-only access

### Authentication
- Admin login URL: `/admin/login`
- Admin middleware checks for 'admin' role
- Uses Spatie Permission package for role/permission management

### User Model Fields
Required fields for admin users:
- `name` - Admin name
- `email` - Login email (unique)
- `password` - Hashed password
- `number` - Phone number
- `country` - Country

Optional fields:
- `marketing` - Marketing consent (default: false)
- `otp_verified` - OTP verification status
- `email_verified_at` - Email verification timestamp

## Running All Seeders

To run all seeders including the admin seeder:
```bash
php artisan db:seed
```

## Security Notes

1. **Change Default Passwords**: Always change default passwords in production
2. **Strong Passwords**: Use strong passwords for admin accounts
3. **Limited Access**: Only create necessary admin accounts
4. **Regular Audits**: Regularly review admin user accounts

## Troubleshooting

### Permission Issues
If you get permission errors, ensure the Spatie Permission tables are migrated:
```bash
php artisan migrate
```

### Role Not Found
If 'admin' role doesn't exist, run the StationSeeder first:
```bash
php artisan db:seed --class=StationSeeder
```

### User Already Exists
The seeders use `updateOrCreate()` so they won't create duplicates. Existing users will be updated with new details.