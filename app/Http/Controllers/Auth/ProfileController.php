<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;
use App\Services\CustomEncryption;

class ProfileController extends Controller
{
    protected $encryptor;

    public function __construct()
    {
        $this->encryptor = new CustomEncryption();
    }

    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        $qrCodeUrl = null;
        $secretKey = null;
        $recoveryCodes = [];

        // Handle 2FA setup if not enabled
        if (!$user->two_factor_enabled) {
            $secretKey = $this->getOrCreateSecretKey($user, $google2fa);
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $secretKey
            );
        }

        // Handle recovery codes
        if (!empty($user->two_factor_recovery_codes)) {
            $recoveryCodes = $this->getValidRecoveryCodes($user);
        }

        return view('profile.show', [
            'user' => $user,
            'qrCodeUrl' => $qrCodeUrl,
            'secretKey' => $secretKey,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.Auth::id(),
        ]);

        Auth::user()->update($request->only('name', 'email'));

        return redirect()->route('profile.show')
            ->with('status', 'Profile updated successfully');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.show')
            ->with('status', 'Password updated successfully');
    }

    /**
     * Get or create 2FA secret key
    */
    protected function getOrCreateSecretKey($user, $google2fa)
    {
        if (empty($user->two_factor_secret)) {
            $secretKey = $google2fa->generateSecretKey();
            $user->two_factor_secret = $this->encryptor->encrypt($secretKey);
            $user->save();
            return $secretKey;
        }

        $decrypted = $this->encryptor->decrypt($user->two_factor_secret);
        
        // Add validation
        if (preg_replace('/[^A-Z2-7]/', '', $decrypted) !== $decrypted) {
            \Log::error('Invalid Base32 characters in secret key', [
                'decrypted' => $decrypted,
                'filtered' => preg_replace('/[^A-Z2-7]/', '', $decrypted)
            ]);
            throw new \RuntimeException('Invalid secret key format');
        }
        
        return $decrypted;
    }

    /**
     * Get valid recovery codes or generate new ones
     */
    protected function getValidRecoveryCodes($user)
    {
        try {
            $decrypted = $this->encryptor->decrypt($user->two_factor_recovery_codes);
            if ($decrypted !== false) {
                $codes = json_decode($decrypted, true) ?? [];
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $codes;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Recovery codes decryption failed: '.$e->getMessage());
        }

        // Generate new codes if decryption fails
        $codes = $this->generateNewRecoveryCodesArray();
        $user->two_factor_recovery_codes = $this->encryptor->encrypt(json_encode($codes));
        $user->save();
        
        return $codes;
    }

    /**
     * Generate new recovery codes
     */
    protected function generateNewRecoveryCodesArray()
    {
        return collect()->times(8, function () {
            return Str::random(10).'-'.Str::random(10);
        })->all();
    }
}