@extends('layouts.app')

@section('content')
 <div class="row justify-content-center">
        <div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Users Management</h5>
            @can('create users')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create User
            </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-primary fw-medium">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-medium">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($user->roles->take(3) as $role)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $role->name }}
                                    </span>
                                    @endforeach
                                    
                                    @if ($user->roles->count() > 3)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        +{{ $user->roles->count() - 3 }} more
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @can('view users')
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                
                                @can('edit users')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('delete users')
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan

                                @can('impersonate users')
                                <form action="{{ route('impersonate.start', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Impersonate this user">
                                        <i class="fas fa-user-check"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No users found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $users->links() }}
        </div>
    </div>
</div>
</div>
@endsection