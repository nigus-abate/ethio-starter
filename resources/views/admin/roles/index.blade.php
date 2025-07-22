@extends('layouts.app')

@section('content')
 <div class="row justify-content-center">
        <div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Roles Management</h5>
            @can('create roles')
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Role
            </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            {{--<td>
                                @foreach($role->permissions as $permission)
                                <span class="badge bg-secondary">{{ $permission->name }}</span>
                                @endforeach
                            </td>--}}

                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($role->permissions->take(3) as $permission)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $permission->name }}
                                    </span>
                                    @endforeach
                                    
                                    @if ($role->permissions->count() > 3)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        +{{ $role->permissions->count() - 3 }} more
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @can('edit roles')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('delete roles')
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
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
            
            {{ $roles->links() }}
        </div>
    </div>
</div>
</div>
@endsection