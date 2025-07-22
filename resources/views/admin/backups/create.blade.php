@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Backup</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('backups.store') }}" method="POST" id="backupForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Backup Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="full">Full Backup</option>
                                <option value="incremental">Incremental Backup</option>
                                <option value="differential">Differential Backup</option>
                            </select>
                            <div class="form-text">
                                <strong>Full:</strong> Complete backup of all data<br>
                                <strong>Incremental:</strong> Only changes since last backup<br>
                                <strong>Differential:</strong> All changes since last full backup
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="backupButton">
                                <i class="fas fa-save me-1"></i> Create Backup
                            </button>
                        </div>
                    </form>

                    <!-- Progress Bar (initially hidden) -->
                    <div id="progressContainer" class="mt-3" style="display: none;">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Backup Progress</span>
                            <span id="progressPercentage">0%</span>
                        </div>
                        <div class="progress">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <small id="progressText">Preparing backup...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.getElementById('backupForm').addEventListener('submit', function(e) {
    const button = document.getElementById('backupButton');
    const progressContainer = document.getElementById('progressContainer');
    
    // Disable button and show progress
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
    progressContainer.style.display = 'block';
    
    // Start polling for progress
    const progressInterval = setInterval(() => {
        fetch("{{ route('backups.progress') }}")
            .then(response => response.json())
            .then(data => {
                if (data.percentage) {
                    document.getElementById('progressBar').style.width = data.percentage + '%';
                    document.getElementById('progressPercentage').textContent = data.percentage + '%';
                    document.getElementById('progressText').textContent = 
                        `Processed ${data.processed} of ${data.total} files`;
                }
                
                if (data.percentage >= 100) {
                    clearInterval(progressInterval);
                }
            });
    }, 1000); // Poll every second

    // In your Blade template
    document.getElementById('cancelBackup').addEventListener('click', function() {
        fetch("{{ route('backups.cancel') }}", { method: 'POST' })
            .then(() => alert('Backup cancelled'));
    });
});
</script>
@endpush
@endsection