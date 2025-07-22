<!-- resources/view/profiles/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light-subtle border-bottom-0 py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-user-cog me-2 text-primary"></i>
                        {{ __('Profile Management') }}
                    </h4>
                </div>

                <div class="card-body p-4">
                    <!-- Session Status -->
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>{{ session('status') }}</span>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif

                    <!-- Profile Information Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light-subtle py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-user-edit me-2 text-primary"></i>
                                {{ __('Profile Information') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> {{ __('Update Profile') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Password Update Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light-subtle py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-key me-2 text-primary"></i>
                                {{ __('Update Password') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="current_password" type="password" 
                                               class="form-control @error('current_password') is-invalid @enderror" 
                                               name="current_password" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="password" type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>

                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="password-confirm" type="password" class="form-control" 
                                               name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i> {{ __('Change Password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Two-Factor Authentication Section -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light-subtle py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                {{ __('Two-Factor Authentication') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->two_factor_enabled)
                                <div class="alert alert-success mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle fa-lg me-3"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">{{ __('2FA is Enabled') }}</h6>
                                            <p class="mb-0">{{ __('Your account is secured with two-factor authentication.') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('recovery-codes.show') }}" class="btn btn-outline-primary flex-grow-1">
                                        <i class="fas fa-shield-alt me-2"></i> {{ __('View Recovery Codes') }}
                                    </a>
                                    <a href="{{ route('two-factor.confirm-disable') }}" class="btn btn-outline-danger flex-grow-1">
                                        <i class="fas fa-times-circle me-2"></i> {{ __('Disable 2FA') }}
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">{{ __('2FA is Not Enabled') }}</h6>
                                            <p class="mb-0">{{ __('Add an extra layer of security to your account.') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($qrCodeUrl)
                                    <div class="mb-4 p-3 border rounded bg-light-subtle">
                                        <div class="text-center mb-4">
                                            <p class="mb-2">{{ __('Scan this QR code with your authenticator app:') }}</p>
                                            <div class="d-flex justify-content-center">
                                                <div class="p-2 bg-white rounded">
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}" 
                                                         alt="QR Code" 
                                                         class="img-fluid" style="max-width: 180px;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label">{{ __('Or enter this secret key manually:') }}</label>
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control font-monospace" 
                                                       value="{{ $secretKey }}" 
                                                       id="secretKey"
                                                       readonly>
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="copyToClipboard('secretKey')"
                                                        title="{{ __('Copy to clipboard') }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">{{ __('Keep this secret key secure') }}</div>
                                        </div>

                                        <form method="POST" action="{{ route('two-factor.enable') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="code" class="form-label">{{ __('Enter authentication code') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                    <input id="code" type="text" 
                                                           class="form-control @error('code') is-invalid @enderror" 
                                                           name="code" required
                                                           placeholder="{{ __('Enter 6-digit code') }}"
                                                           maxlength="6">
                                                    @error('code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-shield-alt me-2"></i> {{ __('Enable 2FA') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .font-monospace {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }
    .input-group-text {
        min-width: 42px;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999);

        try {
            document.execCommand("copy");
            showToast('{{ __("Copied to clipboard!") }}');
        } catch (err) {
            showToast('{{ __("Failed to copy") }}: ' + err, 'danger');
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `position-fixed bottom-0 end-0 m-3 p-3 bg-${type} text-white rounded shadow`;
        toast.style.zIndex = '9999';
        toast.style.transition = 'all 0.5s ease';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>
@endpush