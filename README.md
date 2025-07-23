![Build Status](https://github.com/nigus-abate/ethio-starter/actions/workflows/ci.yml/badge.svg)
![License](https://img.shields.io/github/license/nigus-abate/laravel-starter.svg)
![Laravel](https://img.shields.io/badge/Laravel-12+-red)
<img width="280" height="70" alt="brand" src="https://github.com/user-attachments/assets/cf3c20e3-497a-416e-b2fc-2051867ea1e4" />

# ⚙️ Laravel Starter Kit with 2FA, Roles & Permissions

<img width="1349" height="653" alt="Screenshot 2025-07-23 at 03-59-18 Laravel Starter Dashboard" src="https://github.com/user-attachments/assets/b3e5965e-6de2-4877-8e7c-be8be114ea36" />

A robust Laravel 12+ starter boilerplate packed with:

- ✅ **Two-Factor Authentication (2FA)**
- 🔐 **Role & Permission System** via [Spatie](https://spatie.be/docs/laravel-permission)
- 🧑‍💻 **User Impersonation**
- 📝 **Activity Logging**
- 💾 **Backup & Restore Management**
- 📨 **Notification Center**
- 🧩 **Job Queue Viewer**
- 🎨 **Auto SVG Avatars**
- ⚙️ **Settings Management**
- 🛠 Laravel UI or Breeze compatible

---

## 📦 Features

### ✅ Authentication & Security

- Login, registration, email verification
- Two-Factor Authentication (TOTP-based)
- Password update via profile
- Recovery code support for 2FA

### 🔐 Role & Permission Management

- Fully integrated [Spatie Permission](https://spatie.be/docs/laravel-permission)
- Create, edit, delete roles and permissions
- Role-based middleware protection

### 👥 User & Profile Management

- Manage users with full CRUD support
- Secure password updates
- Avatar upload + SVG fallback based on name

### 🔁 Impersonation

- Admins can impersonate other users
- Prevent impersonation of super-admins

### ⚙️ System Settings

- Centralized config system with editing interface
- Bulk and single-key update support

### 📝 Activity Logs

- Log key actions and changes
- View, delete, or clear logs
- Powered by `LogsActivity` trait

### 💾 Backup System

- Database backup and restoration
- Download backups
- Real-time progress tracking and cancellation

### 📨 Notification Center

- View all user notifications
- Mark individual or all notifications as read

### 🧱 Job Queue Viewer

- View and retry failed jobs
- Check job stats and history

---

## 🛠 Tech Stack

- Laravel 12+
- PHP 8.2+
- MySQL or PostgreSQL
- TailwindCSS + Alpine.js or Bootstrap (UI agnostic)
- Laravel UI or Breeze
- Spatie Laravel Permission
- Laravel Scheduler

---

## 🚀 Getting Started

```bash
git clone https://github.com/nigus-abate/ethio-starter.git
cd ethio-starter

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```
## 📚 Documentation

See the [`docs/`](./docs) folder for feature-specific guides.


✅ Setup Checklist

    Update .env:
```bash
        DB_* – Database connection

        MAIL_* – Mail for 2FA/verification

        FILESYSTEM_DISK – Storage settings for backups
```
🔐 Roles & Permissions

Seeder to create roles, permissions, and users:
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```
Example:
```bash
$user->assignRole('admin');
$user->givePermissionTo('impersonate users');
```
Default Roles & Permissions:

    Admin — Full access to users, roles, logs, settings, backups, and jobs

    User — Limited access (e.g., view reports)

🔁 2FA Setup

User model includes:
```php
'two_factor_secret',
'two_factor_recovery_codes',
'two_factor_enabled' => boolean
```
Usage:

    Enable/disable from /profile

    View or regenerate recovery codes

    Protect routes with middleware: 2fa

📂 Route Overview
Public Routes

    / → Homepage

    /login, /register, /password/reset → Auth routes

Protected Routes (auth, verified, 2fa)

    /dashboard – Authenticated user dashboard

    /profile – Profile settings & 2FA management

    /users – User CRUD

    /roles, /permissions – Role & permission management

    /activity-logs – View and manage logs

    /settings – App config management

    /backups – Backup create, restore, download

    /jobs – View, retry, and manage job queue

    /notifications – Notification center

Impersonation
```php
POST /impersonate/{user}       # Start impersonation
POST /leave-impersonation      # Stop impersonation
```
🧪 Testing

Run full test suite:
```bash
php artisan test
```
    Tip: Use Pest PHP for a more elegant testing experience.

🛡 Security Practices

    Passwords are hashed via Laravel default hashing

    2FA secrets and recovery codes hidden from JSON

    Route middleware includes auth, verified, 2fa, can

    Optional: Rate limiting, CAPTCHA, brute-force protection

📁 Folder Structure

    app/Models/User.php — Roles, 2FA, avatar, impersonation

    app/Http/Controllers/Admin — Admin-specific controllers

    app/Traits/LogsActivity.php — Trait for activity logging

    resources/views/ — Blade views

    routes/web.php — Cleanly grouped & namespaced routes

📄 License

This project is open-source and available under the MIT License.
🤝 Credits

    Laravel

    Spatie Laravel Permission

    Laravel UI

    Niguse Abate

    PRs and contributions welcome.
    If you find it useful, star ⭐ the repo!
