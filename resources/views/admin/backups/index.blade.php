@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Backups</h5>
                    <div>
                        @can('create backups')
                        <a href="{{ route('backups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> New Backup
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @if($backups->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-database fa-4x text-muted mb-3"></i>
                        <p class="text-muted">No backups available</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                <tr>
                                    <td>{{ $backup->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $backup->type === 'full' ? 'primary' : 'info' }}">
                                            {{ ucfirst($backup->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $backup->size ? formatBytes($backup->size) : '--' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $backup->status === 'completed' ? 'success' : ($backup->status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($backup->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $backup->created_at->format('M j, Y H:i') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            @can('download backups')
                                            <a href="{{ route('backups.download', $backup) }}" 
                                               class="btn btn-outline-primary"
                                               @if($backup->status !== 'completed') disabled @endif>
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endcan
                                            @can('view backups')
                                            <a href="{{ route('backups.show', $backup) }}" 
                                               class="btn btn-outline-info">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            @endcan
                                            @can('delete backups')
                                            <form action="{{ route('backups.destroy', $backup) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('Delete this backup?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $backups->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection