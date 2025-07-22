@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="auth-card">
            <div class="auth-header bg-primary text-white">
                <h4 class="mb-1">{{ __('Verify Your Email') }}</h4>
                <p class="mb-0">{{ __('One last step to complete your registration') }}</p>
            </div>
            
            <div class="auth-body text-center">
                <div class="mb-4">
                    <i class="fas fa-envelope-circle-check fa-4x text-primary mb-3"></i>
                    
                    @if (session('resent'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ __('A new verification link has been sent to your email.') }}
                        </div>
                    @endif

                    <p class="mb-3">{{ __('We\'ve sent a verification link to your email address.') }}</p>
                    <p class="mb-4">{{ __('Please check your inbox and click the link to verify your account.') }}</p>
                    
                    <div class="d-inline-block bg-light p-3 rounded mb-4">
                        <p class="mb-2 small text-muted">{{ __('Didn\'t receive the email?') }}</p>
                        <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-paper-plane me-2"></i> {{ __('Resend Verification Email') }}
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="mt-3">
                    <p class="small text-muted mb-2">{{ __('Check your spam folder if you can\'t find the email.') }}</p>
                    <p class="small text-muted">{{ __('Wrong email address?') }} 
                        <a href="{{ route('profile.show') }}" class="text-primary">{{ __('Update your profile') }}</a>
                    </p>
                </div>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0 small">
                    <i class="fas fa-question-circle me-1"></i>
                    {{ __('Having trouble? Contact our support team for help.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection