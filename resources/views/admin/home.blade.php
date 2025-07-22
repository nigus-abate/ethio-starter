@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">My Teams</div>
            <div class="card-body">
                @if(auth()->user()->teams->count() > 0)
                    <ul class="list-group">
                        @foreach(auth()->user()->teams as $team)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('teams.show', $team) }}">{{ $team->name }}</a>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $team->users->count() }} members
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>You're not a member of any teams yet.</p>
                    <a href="{{ route('teams.create') }}" class="btn btn-primary">Create Team</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Recent Reports</div>
            <div class="card-body">
                @if(auth()->user()->reports->count() > 0)
                    <ul class="list-group">
                        @foreach(auth()->user()->reports()->latest()->take(5)->get() as $report)
                            <li class="list-group-item">
                                <a href="{{ route('reports.show', $report) }}">{{ $report->title }}</a>
                                <small class="text-muted d-block">
                                    {{ $report->report_date->format('M d, Y') }} • {{ ucfirst($report->time_period) }}
                                </small>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary mt-2">View All</a>
                @else
                    <p>You haven't created any reports yet.</p>
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">Create Report</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Action Plans</div>
            <div class="card-body">
                @if(auth()->user()->actionPlans->count() > 0)
                    <ul class="list-group">
                        @foreach(auth()->user()->actionPlans()->latest()->take(5)->get() as $actionPlan)
                            <li class="list-group-item">
                                <a href="{{ route('action-plans.show', $actionPlan) }}">{{ $actionPlan->title }}</a>
                                <small class="text-muted d-block">
                                    {{ $actionPlan->frequency }} • {{ $actionPlan->start_date->format('M d, Y') }} to {{ $actionPlan->end_date->format('M d, Y') }}
                                </small>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('action-plans.index') }}" class="btn btn-sm btn-primary mt-2">View All</a>
                @else
                    <p>You haven't created any action plans yet.</p>
                    <a href="{{ route('action-plans.create') }}" class="btn btn-primary">Create Action Plan</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection