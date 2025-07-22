@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header">
                <h4 class="mb-0">{{ __('Welcome Back') }}</h4>
                <p class="mb-0">{{ __('Sign in to continue') }}</p>
            </div>
            
            <div class="auth-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3 input-icon">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" 
                               placeholder="{{ __('Email Address') }}" autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mb-3">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror

                    <div class="mb-3 input-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password" 
                               placeholder="{{ __('Password') }}">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mb-3">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-primary small">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            {{ __('Login') }} <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0">{{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-primary">{{ __('Sign up') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection