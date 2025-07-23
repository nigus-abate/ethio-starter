@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="btn-group">
        <button class="btn btn-outline-primary">Export</button>
        <button class="btn btn-primary">Generate Report</button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row stats-row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total Users</p>
            </div>
            <div class="stat-growth success">
                <i class="fas fa-arrow-up"></i> 12.5%
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['admin_count'] }}</h3>
                <p>Admins</p>
            </div>
            <div class="stat-growth success">
                <i class="fas fa-arrow-up"></i> 5.2%
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['regular_count'] }}</h3>
                <p>Regular Users</p>
            </div>
            <div class="stat-growth success">
                <i class="fas fa-arrow-up"></i> 8.7%
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['completed_backups'] }}</h3>
                <p>Completed Backups</p>
            </div>
            <div class="stat-growth danger">
                <i class="fas fa-arrow-down"></i> 3.1%
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Growth Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>User Growth</h5>
                <div class="card-actions">
                    <select class="form-select form-select-sm" style="width: 120px;">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option selected>Last 90 Days</option>
                        <option>This Year</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="userGrowthChart" class="chart-container"></div>
            </div>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>System Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Storage</span>
                        <span>65% used</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 65%"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Memory</span>
                        <span>42% used</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 42%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>CPU</span>
                        <span>28% used</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 28%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Latest Users -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Latest Users</h5>
                <div class="card-actions">
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->roles->first()->name ?? 'User' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Backups -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Backups</h5>
                @can('view backups')
                <div class="card-actions">
                    <a href="{{ route('backups.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Size</th>
                                <th>Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBackups as $backup)
                                <tr>
                                    <td>{{ $backup->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $backup->status === 'completed' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($backup->status) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($backup->size / 1024 / 1024, 2) }} MB</td>
                                    <td>{{ optional($backup->completed_at)->diffForHumans() ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Recent Activities</h5>
                @can('view activity logs')
                <div class="card-actions">
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $log)
                                <tr>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ optional($log->causer)->name ?? 'System' }}</td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ApexCharts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartOptions = {
            chart: {
                type: 'area',
                height: '100%',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            series: [{
                name: 'New Users',
                data: @json($userGrowthData)
            }],
            xaxis: {
                categories: @json($months),
                labels: {
                    style: { colors: '#64748b' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#64748b' }
                }
            },
            colors: ['#4361ee'],
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4
            },
            tooltip: {
                theme: document.documentElement.getAttribute('data-bs-theme') || 'light'
            }
        };

        const chart = new ApexCharts(document.querySelector("#userGrowthChart"), chartOptions);
        chart.render();
    });
</script>
@endpush
