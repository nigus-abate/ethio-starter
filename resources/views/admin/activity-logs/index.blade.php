@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>Activity Logs</h1>
    <div class="d-flex gap-2">
        {{-- Filters --}}
        <form method="GET" action="{{ route('activity-logs.index') }}" class="d-flex gap-2">
            <div class="input-group">
                <input type="text" name="action" class="form-control form-control-sm" placeholder="Action" value="{{ request('action') }}">
                <input type="text" name="causer" class="form-control form-control-sm" placeholder="Causer" value="{{ request('causer') }}">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-sync-alt me-1"></i> Reset
                </a>
            </div>
        </form>
        
        @can('delete activity logs')
        <form action="{{ route('activity-logs.clear') }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger"
                onclick="return confirm('Are you sure you want to clear all logs?')">
                <i class="fas fa-trash me-1"></i> Clear All
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="card">
    {{-- Delete Selected Logs --}}
    <form method="POST" action="{{ route('activity-logs.deleteSelected') }}">
        @csrf
        @method('DELETE')

        @can('delete activity logs')
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="select-all">
                <label class="form-check-label small text-muted" for="select-all">
                    Select all
                </label>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-danger"
                onclick="return confirm('Are you sure you want to delete the selected logs?')">
                <i class="fas fa-trash-alt me-1"></i> Delete Selected
            </button>
        </div>
        @endcan
        
        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="40"></th>
                        <th>#</th>
                        <th>Action</th>
                        <th>Causer</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Properties</th>
                        <th>IP</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                    <tr>
                        <td>
                            @can('delete activity logs')
                            <input type="checkbox" name="selected_logs[]" value="{{ $log->id }}" class="form-check-input">
                            @endcan
                        </td>
                        <td class="text-muted">{{ $log->id }}</td>
                        <td>
                            <span class="badge 
                                @if(in_array($log->action, ['created', 'success'])) bg-success
                                @elseif(in_array($log->action, ['updated', 'warning'])) bg-warning text-dark
                                @elseif(in_array($log->action, ['deleted', 'error', 'failed'])) bg-danger
                                @else bg-primary
                                @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>
                            @if ($log->causer)
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    @if($log->causer->avatar_url)
                                    <img src="{{ $log->causer->avatar_url }}" alt="{{ $log->causer->name }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                    <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $log->causer->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ class_basename($log->causer_type) }}</small>
                                </div>
                            </div>
                            @else
                            <em class="text-muted">System</em>
                            @endif
                        </td>
                        <td>
                            @if ($log->subject)
                            <div>
                                <span class="fw-medium">{{ $log->subject->name ?? $log->subject->title ?? 'N/A' }}</span>
                                <div class="small text-muted">{{ class_basename($log->subject_type) }}</div>
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>
                            @if ($log->properties)
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#properties-{{ $log->id }}" aria-expanded="false">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="collapse" id="properties-{{ $log->id }}">
                                <pre class="small p-2 bg-light rounded mt-2">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        <td>
                            <div class="small text-muted">{{ $log->created_at->format('Y-m-d') }}</div>
                            <div class="small">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No activity logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="card-footer bg-light">
            {{ $logs->links() }}
        </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="selected_logs[]"]');
        for (const cb of checkboxes) {
            cb.checked = this.checked;
        }
    });
</script>
@endpush