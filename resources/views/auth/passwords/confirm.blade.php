@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header">
                <h4 class="mb-0">{{ __('Confirm Password') }}</h4>
                <p class="mb-0">{{ __('Please confirm your password before continuing') }}</p>
            </div>
            
            <div class="auth-body">
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-4 input-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password"
                               placeholder="{{ __('Enter your password') }}">
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary py-2 px-4">
                            {{ __('Confirm Password') }} <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-primary small">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0">{{ __('Need help?') }} 
                    <a href="#" class="text-primary">{{ __('Contact support') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection