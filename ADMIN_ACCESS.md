# Admin Access Guide

## Default Admin Credentials

An admin user has been created with the following credentials:

- **Email:** `admin@example.com`
- **Password:** `password`

## Login Steps

1. Navigate to `/login` or click "Login" on the homepage
2. Enter the admin credentials above
3. You'll be redirected to `/dashboard`
4. As an admin, you'll have access to all admin features

## Admin Features

Once logged in as admin, you can access:

- **User Management** - View, edit, ban/unban users, manage roles
- **Product Moderation** - Approve/reject seller products
- **Seller Role Requests** - Approve/reject seller role applications
- **Product Management** - Edit or delete any product
- **Withdrawal Management** - Approve/reject seller withdrawal requests
- **Full System Access** - Access all features across the platform

## Creating Additional Admin Users

### Method 1: Using the Seeder
```bash
php artisan db:seed --class=AdminUserSeeder
```
This creates an admin with email `admin@example.com` and password `password`.

### Method 2: Using the Custom Command (Interactive)
```bash
php artisan admin:create
```
This will prompt you for:
- Email address
- Full name
- Password

### Method 3: Using the Custom Command (Non-Interactive)
```bash
php artisan admin:create --email=admin@example.com --name="Admin User" --password=password123
```

### Method 4: Using Tinker
```bash
php artisan tinker
```

Then run:
```php
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => UserRole::ADMIN,
    'email_verified_at' => now(),
    'balance' => 0,
    'is_banned' => false,
]);
```

### Method 5: Promote Existing User to Admin
```bash
php artisan tinker
```

Then run:
```php
use App\Models\User;
use App\Enums\UserRole;

$user = User::where('email', 'user@example.com')->first();
$user->role = UserRole::ADMIN;
$user->save();
```

## User Roles

The system has three roles:

1. **Customer** (`customer`) - Default role for new users
   - Can browse and purchase products
   - Can manage their cart and orders
   - Can request seller role

2. **Seller** (`seller`) - Users who can sell products
   - All customer features
   - Can upload and manage products
   - Can view analytics and earnings
   - Can request withdrawals

3. **Admin** (`admin`) - Full system access
   - All seller features
   - User management
   - Product moderation
   - Seller role request approval
   - Withdrawal management
   - System-wide access

## Security Notes

⚠️ **Important:**
- Change the default admin password immediately in production
- Use strong, unique passwords for admin accounts
- Consider enabling two-factor authentication for admin accounts
- Regularly audit admin user accounts
- Never share admin credentials

## Troubleshooting

### Can't Login?
1. Make sure you've run the seeder: `php artisan db:seed --class=AdminUserSeeder`
2. Check if email verification is disabled (it should be temporarily)
3. Clear cache: `php artisan cache:clear`
4. Check database to confirm user exists: `php artisan tinker` then `User::where('email', 'admin@example.com')->first()`

### Redirected to Email Verification?
Email verification should be disabled. If you're still seeing it:
1. Check `config/fortify.php` - `Features::emailVerification()` should be commented out
2. Check `app/Models/User.php` - `implements MustVerifyEmail` should be commented out
3. Run: `php artisan config:clear`

### Access Denied to Admin Features?
1. Verify your role: `php artisan tinker` then `auth()->user()->role`
2. Make sure the user's role is set to `UserRole::ADMIN`
3. Check middleware in routes - admin routes should use `role:admin` middleware

## Admin Routes

Admin-specific routes are protected by the `role:admin` middleware:

- `/admin/users` - User management
- `/admin/products` - Product moderation
- `/admin/seller-requests` - Seller role requests
- `/admin/withdrawals` - Withdrawal management

These routes are only accessible to users with the `admin` role.
