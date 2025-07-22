# 🛡 Permissions Guide

## Core Concepts
- Roles
- Permissions

## Creating Roles & Permissions
- Run `php artisan db:seed --class=RolesAndPermissionsSeeder`

## Assigning
```php
$user->assignRole('admin');
$user->givePermissionTo('create backups');
```

## Middleware Protection

```php
Route::middleware(['role:admin'])->group(function () {
    //
});
```

## Protecting Resources

    Using can Blade directive

```php
@can('view backups')
    <x-backup-table />
@endcan
```