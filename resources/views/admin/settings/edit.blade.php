@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Setting: {{ $setting->label }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                            Back to Settings
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update', $setting) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="value" class="form-label">{{ $setting->label }}</label>
                            @if($setting->description)
                            <small class="text-muted d-block">{{ $setting->description }}</small>
                            @endif
                            
                            @if($setting->type === 'boolean')
                            <select name="value" id="value" class="form-select">
                                <option value="1" {{ $setting->value ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ !$setting->value ? 'selected' : '' }}>Disabled</option>
                            </select>
                            @elseif($setting->type === 'select' && !empty($setting->options))
                            <select name="value" id="value" class="form-select">
                                @foreach((array)$setting->options as $option)
                                <option value="{{ $option }}" {{ $setting->value == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                            @elseif($setting->type === 'textarea')
                            <textarea name="value" id="value" class="form-control" rows="5">{{ $setting->value }}</textarea>
                            @else
                            <input type="{{ $setting->type }}" name="value" id="value" class="form-control" 
                                value="{{ $setting->value }}">
                            @endif
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Setting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection