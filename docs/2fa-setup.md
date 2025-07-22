# 🔐 Two-Factor Authentication (2FA) Setup

## How It Works
- TOTP-based using Google Authenticator

## Enabling 2FA
- Navigate to `/profile`
- Scan QR code with your Authenticator app

## Disabling 2FA
- Go to `/profile`
- Click "Disable" and confirm

## Recovery Codes
- How to view and regenerate

## Backend Notes
- Fields in `users` table:
  - `two_factor_secret`
  - `two_factor_enabled`
  - `two_factor_recovery_codes`
