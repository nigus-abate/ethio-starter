@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Job Queue Management</h5>
                    <div>
                        <span class="badge bg-primary">
                            Queue: {{ config('queue.default') }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="jobsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" 
                                    data-bs-target="#pending" type="button" role="tab">
                                Pending Jobs ({{ $jobs->total() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="failed-tab" data-bs-toggle="tab" 
                                    data-bs-target="#failed" type="button" role="tab">
                                Failed Jobs ({{ $failedJobs->total() }})
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="{{ route('jobs.stats') }}" class="nav-link" type="button" role="tab">
                                Stats
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content py-3" id="jobsTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            @include('admin.jobs.partials.jobs-table', ['jobs' => $jobs])
                        </div>
                        <div class="tab-pane fade" id="failed" role="tabpanel">
                            @include('admin.jobs.partials.failed-jobs-table', ['jobs' => $failedJobs])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
// Refresh every 30 seconds
setInterval(() => {
    if (window.location.pathname.startsWith('/jobs')) {
        window.location.reload();
    }
}, 30000);
</script>
@endpush
@endsection