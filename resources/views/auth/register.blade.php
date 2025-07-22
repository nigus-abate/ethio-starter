@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header">
                <h4 class="mb-0">{{ __('Create Account') }}</h4>
                <p class="mb-0">{{ __('Get started with your free account') }}</p>
            </div>
            
            <div class="auth-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Full Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               placeholder="{{ __('Enter your name') }}">
                        @error('name')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email"
                               placeholder="{{ __('Enter your email') }}">
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password"
                               placeholder="{{ __('Create a password') }}">
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="{{ __('Confirm your password') }}">
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            {{ __('Register') }} <i class="fas fa-user-plus ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0">{{ __('Already have an account?') }}
                    <a href="{{ route('login') }}" class="text-primary">{{ __('Sign in') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection