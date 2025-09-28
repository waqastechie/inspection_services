@props(['limit' => 5])

@php
    $notifications = auth()->user()->notifications()->latest()->limit($limit)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

@if($notifications->count() > 0)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-bell me-2"></i>
            Notifications
            @if($unreadCount > 0)
                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
            @endif
        </h5>
        <div>
            <a href="#" onclick="markAllAsRead()" class="btn btn-sm btn-outline-primary me-2">
                <i class="fas fa-check-double me-1"></i>Mark All Read
            </a>
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye me-1"></i>View All
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($notifications as $notification)
                <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0 me-2">{{ $notification->data['title'] }}</h6>
                                @if(!$notification->read_at)
                                    <span class="badge bg-primary">New</span>
                                @endif
                            </div>
                            <p class="mb-1 text-muted small">{{ $notification->data['message'] }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex gap-1">
                            @if(isset($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                            @if(!$notification->read_at)
                                <button onclick="markAsRead('{{ $notification->id }}')" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if($notifications->count() >= $limit)
        <div class="card-footer text-center">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">
                View All Notifications
            </a>
        </div>
    @endif
</div>
@else
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-bell me-2"></i>Notifications
        </h5>
    </div>
    <div class="card-body text-center py-4">
        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">No notifications yet</p>
    </div>
</div>
@endif

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
</script>
@endpush