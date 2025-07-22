@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Notifications</h5>
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Mark All as Read</button>
        </form>
    </div>
    <div class="card-body">
        <div class="list-group">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['type'] === 'new_report' ? 
                          route('reports.show', $notification->data['report_id']) :
                          route('action-plans.show', $notification->data['action_plan_id']) }}" 
                   class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $notification->data['message'] }}</h6>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <small>
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link p-0">Mark as read</button>
                            </form>
                        @else
                            Read {{ $notification->read_at->diffForHumans() }}
                        @endif
                    </small>
                </a>
            @empty
                <div class="list-group-item">
                    No notifications found.
                </div>
            @endforelse
        </div>
        
        {{ $notifications->links() }}
    </div>
</div>
@endsection