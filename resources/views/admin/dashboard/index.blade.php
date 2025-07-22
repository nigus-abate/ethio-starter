@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

<!-- Stats Cards -->
<div class="row stats-row mb-4">
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['admin_count'] }}</h3>
                <p>Admins</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-accent">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['regular_count'] }}</h3>
                <p>Regular Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['completed_backups'] }}</h3>
                <p>Completed Backups</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['pending_jobs'] }}</h3>
                <p>Pending Jobs</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-danger">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['failed_jobs'] }}</h3>
                <p>Failed Jobs</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
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
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
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