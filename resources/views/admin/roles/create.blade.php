@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Create New Role') }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Role Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Permissions') }}</label>
                            @error('permissions')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="card mb-3">
                                <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                    <div class="form-check">
                                        <input class="form-check-input group-check-all" 
                                               type="checkbox" 
                                               id="check-all-{{ $group }}"
                                               data-group="{{ $group }}">
                                        <label class="form-check-label small" for="check-all-{{ $group }}">
                                            Check All
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="permission-{{ $permission->id }}"
                                                       data-group="{{ $group }}"
                                                       @if(in_array($permission->id, old('permissions', []))) checked @endif>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> {{ __('Create Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all "Check All" checkboxes
        document.querySelectorAll('.group-check-all').forEach(function(checkbox) {
            const group = checkbox.getAttribute('data-group');
            
            checkbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                checkboxes.forEach(function(permissionCheckbox) {
                    permissionCheckbox.checked = checkbox.checked;
                });
            });
        });

        // Add event listeners to individual permission checkboxes
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            const group = checkbox.getAttribute('data-group');
            
            checkbox.addEventListener('change', function() {
                const groupCheckAll = document.querySelector(`#check-all-${group}`);
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                groupCheckAll.checked = allChecked;
                groupCheckAll.indeterminate = !allChecked && Array.from(checkboxes).some(cb => cb.checked);
            });
        });

        // Initialize the "Check All" states on page load
        document.querySelectorAll('.group-check-all').forEach(function(checkbox) {
            const group = checkbox.getAttribute('data-group');
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkbox.checked = allChecked;
            checkbox.indeterminate = !allChecked && Array.from(checkboxes).some(cb => cb.checked);
        });
    });
</script>
@endpush
@endsection