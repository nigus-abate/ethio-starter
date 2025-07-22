@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Job Details #{{ $job->id }}</h5>
                    <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Jobs
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2">Basic Information</h6>
                                    <div class="mb-3">
                                        <strong>Queue:</strong>
                                        <span class="badge bg-primary">{{ $job->queue }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Attempts:</strong>
                                        <span class="badge bg-{{ $job->attempts > 0 ? 'warning' : 'success' }}">
                                            {{ $job->attempts }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Created At:</strong>
                                        {{ \Carbon\Carbon::createFromTimestamp($job->created_at)->format('Y-m-d H:i:s') }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Scheduled At:</strong>
                                        {{ \Carbon\Carbon::createFromTimestamp($job->available_at)->format('Y-m-d H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2">Job Status</h6>
                                    <div class="alert alert-{{ $job->reserved_at ? 'info' : 'secondary' }}">
                                        <i class="fas fa-{{ $job->reserved_at ? 'play' : 'pause' }} me-2"></i>
                                        {{ $job->reserved_at ? 'Processing (reserved)' : 'Waiting in queue' }}
                                    </div>
                                    @if($job->reserved_at)
                                    <div class="mb-3">
                                        <strong>Reserved At:</strong>
                                        {{ \Carbon\Carbon::createFromTimestamp($job->reserved_at)->format('Y-m-d H:i:s') }}
                                    </div>
                                    @endif
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" style="width: {{ min($job->attempts * 20, 100) }}%">
                                            Attempt {{ $job->attempts }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Payload Data</h6>
                        </div>
                        <div class="card-body">
                            @php
                                try {
                                    $payload = json_decode($job->payload);
                                    $command = unserialize($payload->data->command);
                                    $formattedPayload = json_encode($payload, JSON_PRETTY_PRINT);
                                } catch (Exception $e) {
                                    $formattedPayload = "Unable to decode payload: " . $e->getMessage();
                                }
                            @endphp

                            <pre class="bg-dark text-white p-3 rounded"><code>{{ $formattedPayload }}</code></pre>
                        </div>
                    </div>

                    @if(isset($command) && method_exists($command, 'displayName'))
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Job Details</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Job Class:</dt>
                                <dd class="col-sm-9">{{ get_class($command) }}</dd>

                                <dt class="col-sm-3">Display Name:</dt>
                                <dd class="col-sm-9">{{ $command->displayName() }}</dd>

                                @if(method_exists($command, 'tags'))
                                <dt class="col-sm-3">Tags:</dt>
                                <dd class="col-sm-9">
                                    @foreach($command->tags() as $tag)
                                        <span class="badge bg-info me-1">{{ $tag }}</span>
                                    @endforeach
                                </dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        max-height: 400px;
        overflow-y: auto;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush

@push('scripts')
<script>
// Auto-refresh the page every 30 seconds if job is not reserved
@if(!$job->reserved_at)
setTimeout(() => {
    window.location.reload();
}, 30000);
@endif
</script>
@endpush