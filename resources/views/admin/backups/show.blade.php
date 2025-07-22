@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Backup Details</h5>
                    <div>
                        <a href="{{ route('backups.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Backups
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-database fa-4x text-primary"></i>
                                    </div>
                                    <h5>{{ $backup->name }}</h5>
                                    <div class="mt-2">
                                        <span class="badge bg-{{ $backup->status === 'completed' ? 'success' : ($backup->status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($backup->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <strong>Type:</strong>
                                                <span class="badge bg-{{ $backup->type === 'full' ? 'primary' : 'info' }}">
                                                    {{ ucfirst($backup->type) }}
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Size:</strong>
                                                {{ $backup->size ? formatBytes($backup->size) : '--' }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Storage Disk:</strong>
                                                {{ ucfirst($backup->disk) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <strong>Created At:</strong>
                                                {{ $backup->created_at->format('M j, Y H:i:s') }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Completed At:</strong>
                                                {{ $backup->completed_at ? $backup->completed_at->format('M j, Y H:i:s') : '--' }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Created By:</strong>
                                                {{ $backup->user->name ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Backup Contents</h6>
                        </div>
                        <div class="card-body">
                            @if(isset($backup->metadata['contents']))
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-{{ $backup->metadata['contents']['database'] ? 'success' : 'danger' }}">
                                        <i class="fas fa-database me-2"></i>
                                        Database: {{ $backup->metadata['contents']['database'] ? 'Included' : 'Not Included' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-{{ $backup->metadata['contents']['storage'] ? 'success' : 'danger' }}">
                                        <i class="fas fa-folder me-2"></i>
                                        Storage: {{ $backup->metadata['contents']['storage'] ? 'Included' : 'Not Included' }}
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No content information available
                            </div>
                            @endif
                        </div>
                    </div>

                    @if(isset($backup->metadata['error']))
                    <div class="card mb-4 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0">Error Details</h6>
                        </div>
                        <div class="card-body">
                            <code>{{ $backup->metadata['error'] }}</code>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    @can('download backups')
                                    <a href="{{ route('backups.download', $backup) }}" 
                                       class="btn btn-primary me-2"
                                       @if($backup->status !== 'completed') disabled @endif>
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                    @endcan
                                    @can('restore backups')
                                    @if($backup->status === 'completed')
                                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#restoreModal">
                                        <i class="fas fa-trash-restore me-1"></i> Restore
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                                @can('delete backups')
                                <form action="{{ route('backups.destroy', $backup) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this backup?')">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restore Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restore Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('backups.restore', $backup) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This will overwrite existing data with the backup contents.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Restore Options</label>
                        
                        @if($backup->restore_options['database'])
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="restoreDatabase" name="restore_database" value="1" checked>
                            <label class="form-check-label" for="restoreDatabase">
                                Restore Database
                            </label>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Database not included in this backup
                        </div>
                        @endif
                        
                        @if($backup->restore_options['storage'])
                         <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="restoreStorage" name="restore_storage" value="1" checked>
                            <label class="form-check-label" for="restoreStorage">
                                Restore Files
                            </label>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Files not included in this backup
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"
                            @if(!$backup->isRestorable()) disabled @endif>
                        <i class="fas fa-trash-restore me-1"></i> Confirm Restore
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('styles')
<style type="text/css">
    /* Backup status badges */
.badge-backup {
    font-size: 0.8rem;
    padding: 0.35em 0.65em;
}

/* Backup cards */
.backup-card {
    transition: all 0.2s;
    border-left: 4px solid transparent;
}

.backup-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

.backup-card.success {
    border-left-color: var(--bs-success);
}

.backup-card.warning {
    border-left-color: var(--bs-warning);
}

.backup-card.danger {
    border-left-color: var(--bs-danger);
}

/* Backup actions */
.backup-actions .btn {
    min-width: 120px;
}
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Disable restore options based on backup contents
    @if(isset($backup->metadata['contents']))
        @if(!$backup->metadata['contents']['database'])
            document.getElementById('restoreDatabase').disabled = true;
            document.getElementById('restoreDatabase').checked = false;
        @endif
        @if(!$backup->metadata['contents']['storage'])
            document.getElementById('restoreStorage').disabled = true;
            document.getElementById('restoreStorage').checked = false;
        @endif
    @endif

    const restoreForm = document.querySelector('#restoreModal form');
    
    if (restoreForm) {
        restoreForm.addEventListener('submit', function(e) {
            const dbCheck = document.getElementById('restoreDatabase');
            const storageCheck = document.getElementById('restoreStorage');
            
            if ((!dbCheck || !dbCheck.checked) && (!storageCheck || !storageCheck.checked)) {
                e.preventDefault();
                alert('Please select at least one restore option');
            }
        });
    }
});
</script>
@endpush