<!-- resources/view/profiles/recovery-codes.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Two-Factor Recovery Codes') }}</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(empty($codes))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> 
                            No recovery codes found. New codes have been generated for you.
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Important!</h5>
                                <p class="mb-1">These codes can be used to access your account if you lose your authenticator device.</p>
                                <p class="mb-0"><strong>Each code can only be used once.</strong></p>
                            </div>

                            <div class="list-group">
                                @foreach($codes as $code)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="font-monospace" id="code-text-{{ $loop->index }}">{{ $code }}</span>
                                        <button class="btn btn-sm btn-outline-secondary copy-btn" 
                                                data-code="{{ $code }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <form method="POST" action="{{ route('recovery-codes.generate') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-redo"></i> Generate New Codes
                            </button>
                        </form>

                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-monospace {
        font-family: monospace;
        font-size: 1.1em;
        letter-spacing: 0.5px;
    }
    .copy-btn {
        min-width: 38px;
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event to all copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            copyToClipboard(code);
        });
    });

    function copyToClipboard(text) {
        // Create a temporary textarea element
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';  // Prevent scrolling to bottom
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            const successful = document.execCommand('copy');
            const message = successful ? 'Copied to clipboard!' : 'Unable to copy';
            showToast(message);
        } catch (err) {
            showToast('Error copying: ' + err);
        }
        
        document.body.removeChild(textarea);
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded shadow';
        toast.style.zIndex = '9999';
        toast.style.transition = 'opacity 0.5s';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 2000);
    }
});
</script>
@endpush