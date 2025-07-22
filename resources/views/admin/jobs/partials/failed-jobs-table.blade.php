<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Connection</th>
                <th>Queue</th>
                <th>Failed At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->connection }}</td>
                <td>{{ $job->queue }}</td>
                <td>{{ \Carbon\Carbon::parse($job->failed_at)->format('Y-m-d H:i:s') }}</td>
                <td>
                    @can('retry jobs')
                    <form action="{{ route('jobs.retry', $job->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-redo"></i> Retry
                        </button>
                    </form>
                    @endcan
                     @can('delete jobs')
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $job->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No failed jobs</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $jobs->links() }}
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this failed job?')) {
        fetch(`/jobs/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            window.location.reload();
        });
    }
}
</script>
@endpush