<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\Services\CustomEncryption;

class TwoFactorAuthController extends Controller
{

    protected $encryptor;

    public function __construct()
    {
        $this->encryptor = new CustomEncryption();
    }
    
    public function showVerifyForm()
    {
        // if (!session('encrypted_download_request') && !session('2fa_required')) {
        //     return redirect()->route('home');
        // }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required_without:recovery_code|string',
            'recovery_code' => 'required_without:code|string'
        ]);

        $user = Auth::user();

        if ($request->filled('code')) {
            // Decrypt the secret before verification
            $secretKey = $this->encryptor->decrypt($user->two_factor_secret);
            
            \Log::debug('2FA Verification Attempt', [
                'user_id' => $user->id,
                'decrypted_secret' => $secretKey,
                'code_attempt' => $request->code
            ]);

            $valid = (new Google2FA)->verifyKey(
                $secretKey, // Use the decrypted secret
                $request->code
            );

            if (!$valid) {
                return back()->withErrors(['code' => 'Invalid authentication code']);
            }
        } else {
            $recoveryCodes = json_decode($this->encryptor->decrypt($user->two_factor_recovery_codes), true);
            
            if (!in_array($request->recovery_code, $recoveryCodes)) {
                return back()->withErrors(['recovery_code' => 'Invalid recovery code']);
            }

            // Remove used code and update
            $updatedCodes = array_diff($recoveryCodes, [$request->recovery_code]);
            $user->two_factor_recovery_codes = $this->encryptor->encrypt(json_encode($updatedCodes));
            $user->save();
        }

        Session::put('2fa_verified', true);

        if (session('encrypted_download_request')) {
            return redirect()->route('documents.download', session('encrypted_download_request'));
        }

        return redirect()->intended();
    }

    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user = $request->user();
        $google2fa = new Google2FA();

        // Always decrypt the secret before verification
        $secretKey = $this->encryptor->decrypt($user->two_factor_secret);
        
        // Debug log to verify the decrypted key
        \Log::debug('2FA Verification Attempt', [
            'user_id' => $user->id,
            'decrypted_secret' => $secretKey,
            'code_attempt' => $request->code
        ]);

        $valid = $google2fa->verifyKey($secretKey, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid authentication code']);
        }

        $user->forceFill([
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => $this->generateRecoveryCodes(),
        ])->save();

        return redirect()->route('profile.show')
            ->with('status', 'Two-factor authentication enabled successfully');
    }

    public function showDisableConfirmation()
    {
        return view('auth.two-factor-disable-confirm');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = $request->user();

        // Log before disabling for audit purposes
        \Log::info('Disabling 2FA for user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_recovery_codes' => null,
        ])->save();

        // Optional: Clear any 2FA-related sessions
        Session::forget('2fa_verified');
        Session::forget('encrypted_download_request');

        return redirect()->route('profile.show')
            ->with('status', 'Two-factor authentication has been disabled');
    }

    public function showRecoveryCodes()
    {
        $user = Auth::user();
        
        if (!$user->two_factor_enabled) {
             return redirect()->route('profile.show')
            ->with('error', 'Two-factor authentication has not enabled');
        }
        try {
            $codes = [];
            if ($user->two_factor_recovery_codes) {
                $decrypted = $this->encryptor->decrypt($user->two_factor_recovery_codes);
                $codes = json_decode($decrypted, true) ?? [];
                
            // Debug output - remove after testing
                \Log::debug('Recovery codes:', [
                    'encrypted' => $user->two_factor_recovery_codes,
                    'decrypted' => $decrypted,
                    'decoded' => $codes
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to decrypt recovery codes: '.$e->getMessage());
            $codes = $this->generateNewRecoveryCodesArray();
            $user->forceFill([
                'two_factor_recovery_codes' => $this->encryptor->encrypt(json_encode($codes))
            ])->save();
        }

        return view('profile.recovery-codes', [
            'codes' => $codes
        ]);
    }

    public function generateNewRecoveryCodes()
    {
        $codes = $this->generateNewRecoveryCodesArray();
        
        Auth::user()->forceFill([
            'two_factor_recovery_codes' => $this->encryptor->encrypt(json_encode($codes)),
        ])->save();

        return redirect()->route('profile.show')
        ->with('status', 'Recovery codes regenerated successfully');
    }

    protected function generateRecoveryCodes()
    {
        return $this->encryptor->encrypt(json_encode(
            $this->generateNewRecoveryCodesArray()
        ));
    }

    protected function generateNewRecoveryCodesArray()
    {
        return collect()->times(8, function () {
            return Str::random(10).'-'.Str::random(10);
        })->all();
    }
}