# 🧑‍💻 User Impersonation Guide

This feature allows privileged users (e.g., admins) to log in as another user to assist with support, debugging, or user simulation — without needing their credentials.

---

## 🚀 Features

- Start/stop impersonation via dedicated routes
- Protect sensitive accounts (like `super-admin`) from being impersonated
- Permissions-based access (e.g., only users with `impersonate users` permission)

---

## 🔐 Access Control

Only users with the `impersonate users` permission can initiate impersonation.

```php
auth()->user()->canImpersonate(); // true/false
```
A user can be impersonated only if:

$user->canBeImpersonated(); // true/false

By default, super-admins are protected from impersonation.
🔁 Starting Impersonation
Route

POST /impersonate/{user}

Controller Logic

public function start(User $user)
{
    if (!auth()->user()->canImpersonate() || !$user->canBeImpersonated()) {
        abort(403);
    }

    session(['impersonated_by' => auth()->id()]);
    auth()->login($user);

    return redirect('/dashboard');
}

🚪 Stopping Impersonation
Route

POST /leave-impersonation

Controller Logic

public function stop()
{
    $originalUserId = session()->pull('impersonated_by');
    if ($originalUserId) {
        auth()->loginUsingId($originalUserId);
    }

    return redirect('/users');
}

🧠 Blade Helpers

Use these to show banners or restrict views while impersonating:

@if(session()->has('impersonated_by'))
    <div class="alert alert-warning">
        You are impersonating {{ auth()->user()->name }}.
        <form method="POST" action="{{ route('impersonate.stop') }}">
            @csrf
            <button type="submit">Leave Impersonation</button>
        </form>
    </div>
@endif

🔒 Best Practices

    Always require explicit permission (impersonate users) via policies or middleware

    Log impersonation events for auditing

    Show a clear UI banner when impersonating

    Protect sensitive roles (e.g., super-admin) using logic like:

public function canBeImpersonated(): bool
{
    return !$this->hasRole('super-admin') || auth()->user()->hasRole('super-admin');
}

📖 Related

    2FA Setup

    Permissions Guide

    Activity Logs

