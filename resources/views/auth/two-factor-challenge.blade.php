<!-- resources/views/auth/two-factor-challenge.blade.php -->
@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header bg-primary text-white">
                <h4 class="mb-1">{{ __('Two Factor Authentication') }}</h4>
                <p class="mb-0">{{ __('Verify your identity') }}</p>
            </div>
            
            <div class="auth-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <p class="text-muted">
                        {{ __('Please enter your authentication code or use a recovery code') }}
                    </p>
                </div>

                <!-- Authentication Code Form -->
                <form method="POST" action="{{ route('2fa.verify.submit') }}" class="mb-4">
                    @csrf

                    <div class="mb-3 input-icon">
                        <i class="fas fa-mobile-alt"></i>
                        <input id="code" type="text" 
                               class="form-control @error('code') is-invalid @enderror" 
                               name="code" required autocomplete="one-time-code" 
                               placeholder="{{ __('6-digit authentication code') }}" autofocus>
                        @error('code')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            {{ __('Verify Identity') }} <i class="fas fa-shield-alt ms-2"></i>
                        </button>
                    </div>
                </form>

                <!-- Recovery Code Toggle -->
                <div class="text-center mb-3">
                    <a href="#" class="text-primary" data-bs-toggle="collapse" data-bs-target="#recoveryCodeForm">
                        <i class="fas fa-key me-1"></i> {{ __('Use recovery code instead') }}
                    </a>
                </div>

                <!-- Recovery Code Form -->
                <div class="collapse" id="recoveryCodeForm">
                    <form method="POST" action="{{ route('2fa.verify.submit') }}" class="mt-3">
                        @csrf

                        <div class="mb-3 input-icon">
                            <i class="fas fa-key"></i>
                            <input id="recovery_code" type="text" 
                                   class="form-control @error('recovery_code') is-invalid @enderror" 
                                   name="recovery_code" required 
                                   placeholder="{{ __('8-digit recovery code') }}">
                            @error('recovery_code')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-outline-primary w-100 py-2">
                                {{ __('Verify with Recovery Code') }} <i class="fas fa-unlock ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0 small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ __('Having trouble? Contact support if you need assistance') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection