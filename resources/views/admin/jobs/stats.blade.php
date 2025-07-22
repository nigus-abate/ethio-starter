@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Queue Statistics Dashboard</h5>
                    <div>
                        <span class="badge bg-primary">
                            Last Updated: {{ now()->format('Y-m-d H:i:s') }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Stats Cards Row -->
                    <div class="row mb-4">
                        <!-- Today's Jobs -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-start border-primary border-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title text-muted">Jobs Processed Today</h6>
                                            <h2 class="mb-0">{{ $stats['today'] }}</h2>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-tasks fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="text-success">
                                            <i class="fas fa-arrow-up"></i> 
                                            {{ $stats['today'] > 0 ? round($stats['today']/$stats['today'] * 100) : 0 }}% from yesterday
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Failed Jobs -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-start border-danger border-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title text-muted">Failed Jobs Today</h6>
                                            <h2 class="mb-0">{{ $stats['failed_today'] }}</h2>
                                        </div>
                                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="{{ $stats['failed_today'] > 0 ? 'text-danger' : 'text-success' }}">
                                            @if($stats['failed_today'] > 0)
                                                <i class="fas fa-arrow-up"></i>
                                                {{ $stats['failed_today'] }} need attention
                                            @else
                                                <i class="fas fa-check-circle"></i> No failures
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Processing Time -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-start border-success border-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title text-muted">Avg Processing Time</h6>
                                            <h2 class="mb-0">
                                                {{ $stats['avg_time'] ? round($stats['avg_time']/60, 2) : 0 }} <small>min</small>
                                            </h2>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-clock fa-2x text-success"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="{{ $stats['avg_time'] > 300 ? 'text-danger' : 'text-success' }}">
                                            @if($stats['avg_time'] > 300)
                                                <i class="fas fa-exclamation-circle"></i> Slow processing
                                            @else
                                                <i class="fas fa-check-circle"></i> Within threshold
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Jobs Last 7 Days</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="jobsChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Queue Distribution</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="queuesChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between">
                            <h6 class="mb-0">Recent Job Activity</h6>
                            <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-primary">
                                View All Jobs
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Queue</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Time Taken</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentJobs as $job)
                                        <tr>
                                            <td>{{ $job->id }}</td>
                                            <td>{{ $job->queue }}</td>
                                            <td>
                                                @if($job->reserved_at)
                                                    <span class="badge bg-info">Processing</span>
                                                @elseif($job->completed_at)
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-secondary">Queued</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($job->created_at)->format('H:i:s') }}</td>

                                            <td>
                                                @if($job->reserved_at && $job->completed_at)
                                                    {{ round(($job->completed_at->timestamp - $job->reserved_at->timestamp)/60, 2) }} min
                                                @elseif($job->reserved_at)
                                                    Processing...
                                                @else
                                                    --
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .border-4 {
        border-width: 4px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Jobs Over Time Chart
const jobsCtx = document.getElementById('jobsChart').getContext('2d');
new Chart(jobsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($jobTrends['dates']) !!},
        datasets: [{
            label: 'Jobs Processed',
            data: {!! json_encode($jobTrends['counts']) !!},
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Queue Distribution Chart
const queuesCtx = document.getElementById('queuesChart').getContext('2d');
new Chart(queuesCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($queueDistribution->pluck('queue')) !!},
        datasets: [{
            data: {!! json_encode($queueDistribution->pluck('count')) !!},
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
            ],
            hoverBackgroundColor: [
                '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            }
        }
    }
});

// Auto-refresh every 60 seconds
setTimeout(() => {
    window.location.reload();
}, 60000);
</script>
@endpush