@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Details: {{ $user->name }}</h5>
                    <div>
                        @can('edit users')
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Name:</div>
                        <div class="col-md-8">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Created At:</div>
                        <div class="col-md-8">{{ $user->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Roles:</div>
                        <div class="col-md-8">
                            @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 fw-bold">Permissions:</div>
                        <div class="col-md-8">
                            @foreach($user->getAllPermissions() as $permission)
                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection