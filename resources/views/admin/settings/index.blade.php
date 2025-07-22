@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Application Settings</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('settings.bulk-update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="accordion" id="settingsAccordion">
                            @foreach($settings as $group => $groupSettings)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ Str::studly($group) }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#collapse{{ Str::studly($group) }}" 
                                    aria-expanded="true" aria-controls="collapse{{ Str::studly($group) }}">
                                    {{ ucfirst($group) }} Settings
                                </button>
                            </h2>
                            <div id="collapse{{ Str::studly($group) }}" class="accordion-collapse collapse show" 
                            aria-labelledby="heading{{ Str::studly($group) }}" data-bs-parent="#settingsAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    @foreach($groupSettings as $setting)
                                    <div class="col-md-6 mb-3">
                                        <label for="setting_{{ $setting->key }}" class="form-label">
                                            {{ $setting->label }}
                                            @if($setting->description)
                                            <small class="text-muted d-block">{{ $setting->description }}</small>
                                            @endif
                                        </label>
                                        
                                        @if($setting->type === 'boolean')
                                        <select name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                            class="form-select">
                                            <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled</option>
                                            <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                        @elseif($setting->type === 'select' && !empty($setting->options))
                                        <select name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                            class="form-select">
                                            @php
                                                $options = is_array($setting->options) ? $setting->options : json_decode($setting->options, true);
                                            @endphp

                                            @foreach($options as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" {{ $setting->value == $optionValue ? 'selected' : '' }}>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @elseif($setting->type === 'file')
                                            @if($setting->value)
                                                <div class="mb-2">
                                                    <p>Current File:</p>
                                                    <img src="{{ asset('storage/' . $setting->value) }}" alt="File Preview" style="max-width: 150px;" onerror="this.style.display='none'; console.error('Image load failed: {{ asset('storage/' . $setting->value) }}')">
                                                   <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_file[{{ $setting->key }}]" id="remove_{{ $setting->key }}">
                <label class="form-check-label" for="remove_{{ $setting->key }}">Remove current file</label>
            </div>
                                                </div>
                                            @endif
                                            <input type="file" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
    class="form-control" 
    onchange="console.log('File selected:', {
        name: this.files[0]?.name,
        size: this.files[0]?.size,
        type: this.files[0]?.type
    })">
    
                                            
                                        @elseif($setting->type === 'textarea')
                                        <textarea name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                            class="form-control" rows="3">{{ $setting->value }}</textarea>
                                            @else
                                            <input type="{{ $setting->type }}" name="settings[{{ $setting->key }}]" 
                                            id="setting_{{ $setting->key }}" class="form-control" 
                                            value="{{ $setting->value }}">
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection