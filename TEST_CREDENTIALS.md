# Test Credentials for Commission Shop

This document provides test credentials for all user roles in the Commission Shop application.

## Authentication Method

The application uses **username-based login** (not email). You can log in using either:
- The `name` field (username) - Recommended for testing
- The `email` field - Also supported

## Test Credentials

### Super Admin
- **Username:** `superadmin`
- **Password:** `superadmin`
- **Role:** Super Admin
- **Access:** Full access to all features including role management and user management

### Admin
- **Username:** `admin`
- **Password:** `admin`
- **Role:** Admin
- **Access:** Full access to all features except role management (cannot create/modify roles)

### User
- **Username:** `user`
- **Password:** `user`
- **Role:** User
- **Access:** Limited access (view dashboard, sales, purchases, and stock)

### Operator (Legacy)
- **Username:** `operator`
- **Password:** `operator`
- **Role:** Operator
- **Access:** Limited permissions (can manage sales and purchases, view dues and stock)

## How to Use

1. Navigate to the login page: `/login`
2. Enter the username (e.g., `superadmin`, `admin`, or `user`)
3. Enter the corresponding password
4. Click "Sign in"

## Notes

- All passwords are intentionally simple for testing purposes
- These credentials are created automatically when running `php artisan db:seed`
- For production, change all passwords immediately
- The authentication system supports both username (`name` field) and email login
