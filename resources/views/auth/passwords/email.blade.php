@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header">
                <h4 class="mb-0">{{ __('Reset Password') }}</h4>
                <p class="mb-0">{{ __('Enter your email to receive a reset link') }}</p>
            </div>
            
            <div class="auth-body">
                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4 input-icon">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="{{ __('Email Address') }}">
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            {{ __('Send Password Reset Link') }} <i class="fas fa-paper-plane ms-2"></i>
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