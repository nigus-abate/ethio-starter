@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header">
                <h4 class="mb-0">{{ __('Reset Password') }}</h4>
                <p class="mb-0">{{ __('Create your new password') }}</p>
            </div>
            
            <div class="auth-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3 input-icon">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" 
                               placeholder="{{ __('Email Address') }}" autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 input-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password"
                               placeholder="{{ __('New Password') }}">
                        <div class="form-text small">Minimum 8 characters</div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 input-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="{{ __('Confirm New Password') }}">
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            {{ __('Reset Password') }} <i class="fas fa-key ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0">{{ __('Remember your password?') }}
                    <a href="{{ route('login') }}" class="text-primary">{{ __('Sign in') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection