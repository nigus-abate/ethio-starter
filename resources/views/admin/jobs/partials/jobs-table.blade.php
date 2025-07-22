<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Queue</th>
                <th>Attempts</th>
                <th>Scheduled At</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->queue }}</td>
                <td>{{ $job->attempts }}</td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($job->available_at)->format('Y-m-d H:i:s') }}</td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($job->created_at)->format('Y-m-d H:i:s') }}</td>
                <td>
                    @can('view jobs')
                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No pending jobs</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $jobs->links() }}
</div>