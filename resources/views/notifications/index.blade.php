@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </h1>
                    <p class="text-muted mb-0">Stay updated with your inspection activities</p>
                </div>
                <div class="d-flex gap-2">
                    @if($notifications->where('read_at', null)->count() > 0)
                        <button onclick="markAllAsRead()" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-2"></i>Mark All as Read
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($notifications->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h5 class="mb-0 me-3">{{ $notification->data['title'] }}</h5>
                                                        @if(!$notification->read_at)
                                                            <span class="badge bg-primary">New</span>
                                                        @endif
                                                    </div>
                                                    <p class="mb-2 text-muted">{{ $notification->data['message'] }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="btn-group" role="group">
                                                @if(isset($notification->data['action_url']))
                                                    <a href="{{ $notification->data['action_url'] }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                @endif
                                                @if(!$notification->read_at)
                                                    <button onclick="markAsRead('{{ $notification->id }}')" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fas fa-check me-1"></i>Mark Read
                                                    </button>
                                                @endif
                                                <button onclick="deleteNotification('{{ $notification->id }}')" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if($notifications->hasPages())
                        <div class="card-footer">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No Notifications</h4>
                        <p class="text-muted">You don't have any notifications yet. They'll appear here when there are updates to your inspections.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    if (!confirm('Are you sure you want to mark all notifications as read?')) {
        return;
    }

    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }

    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush