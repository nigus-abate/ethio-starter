@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header bg-danger text-white">
                <i class="fas fa-shield-alt me-2"></i>
                <h4 class="d-inline-block mb-0">{{ __('Disable Two-Factor Authentication') }}</h4>
            </div>
            
            <div class="auth-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                    </div>
                    <h5 class="fw-bold text-danger mb-2">{{ __('Security Warning') }}</h5>
                    <p class="text-muted">
                        {{ __('Disabling 2FA will significantly reduce your account security.') }}
                        {{ __('You will need to set it up again if you want to re-enable it.') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('two-factor.disable') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4 input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" 
                               name="password" required autocomplete="current-password"
                               placeholder="{{ __('Enter your password to confirm') }}">
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Go Back') }}
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-power-off me-1"></i> {{ __('Disable 2FA') }}
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="auth-footer bg-light-danger">
                <p class="mb-0 small text-danger">
                    <i class="fas fa-lightbulb me-1"></i>
                    {{ __('Recommendation: Keep 2FA enabled for maximum account security.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-top: 1px solid rgba(220, 53, 69, 0.2);
    }
    .text-light-danger {
        color: rgba(220, 53, 69, 0.8);
    }
</style>
@endsection