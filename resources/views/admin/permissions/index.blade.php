@extends('layouts.app')

@section('content')
 <div class="row justify-content-center">
        <div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Permissions Management</h5>
            @can('create permissions')
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Permission
            </a>
            @endcan
        </div>

        <div class="card-body">
            @foreach($permissions as $group => $groupPermissions)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPermissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @can('edit permissions')
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('delete permissions')
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</div>
@endsection