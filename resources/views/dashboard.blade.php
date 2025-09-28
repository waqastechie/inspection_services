@extends('layouts.master')

@section('title', 'Dashboard - Inspection Services')

@push('styles')
<style>
    .btn-outline-secondary:hover {
        color: #6c757d !important;
        background-color: #f8f9fa !important;
        border-color: #6c757d !important;
    }
    
    .btn-outline-secondary:focus,
    .btn-outline-secondary:active {
        color: #6c757d !important;
        background-color: #e9ecef !important;
        border-color: #6c757d !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </h1>
                    <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
        <!-- Admin Dashboard -->
        <div class="row g-4 mb-4">
            <!-- Stats Cards -->
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Users</h6>
                                <h2 class="mb-0">{{ App\Models\User::count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Active Clients</h6>
                                <h2 class="mb-0">{{ App\Models\Client::active()->count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-building fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Personnel</h6>
                                <h2 class="mb-0">{{ App\Models\Personnel::active()->count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-tie fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Inspections</h6>
                                <h2 class="mb-0">{{ App\Models\Inspection::count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Urgent Actions Alert -->
        @php
            $pendingQA = App\Models\Inspection::where('qa_status', 'pending_qa')->count();
            $needsRevision = App\Models\Inspection::where('qa_status', 'revision_required')->count();
            $underReview = App\Models\Inspection::where('qa_status', 'under_qa_review')->count();
            $unreadNotifications = auth()->user()->unreadNotifications()->count();
        @endphp
        
        @if($pendingQA > 0 || $needsRevision > 0 || $underReview > 0 || $unreadNotifications > 0)
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <h5 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-2"></i>Action Required
                    </h5>
                    <div class="row">
                        @if($pendingQA > 0)
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <span><strong>{{ $pendingQA }}</strong> inspections pending QA review</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($needsRevision > 0)
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit text-danger me-2"></i>
                                <span><strong>{{ $needsRevision }}</strong> inspections need revision</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($underReview > 0)
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-eye text-info me-2"></i>
                                <span><strong>{{ $underReview }}</strong> inspections under review</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($unreadNotifications > 0)
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bell text-primary me-2"></i>
                                <span><strong>{{ $unreadNotifications }}</strong> unread notifications</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        @if($pendingQA > 0 && Auth::user()->canApproveInspections())
                            <a href="{{ route('qa.pending') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-clipboard-check me-1"></i>Review Pending QA
                            </a>
                        @endif
                        @if($unreadNotifications > 0)
                            <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-bell me-1"></i>View Notifications
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->isSuperAdmin())
        <!-- Super Admin Only - System Monitoring -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">System Errors</h6>
                                <h2 class="mb-0">{{ App\Models\SystemLog::where('level', 'error')->count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-dark bg-opacity-25">
                        <a href="{{ route('admin.logs.system') }}" class="text-white text-decoration-none">
                            <i class="fas fa-eye me-1"></i> View System Logs
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">User Activities</h6>
                                <h2 class="mb-0">{{ App\Models\ActivityLog::count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-dark bg-opacity-25">
                        <a href="{{ route('admin.logs.activity') }}" class="text-white text-decoration-none">
                            <i class="fas fa-eye me-1"></i> View Activity Logs
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">
                                    <i class="fas fa-chart-line me-2"></i>
                                    System Monitoring Dashboard
                                </h6>
                                <p class="mb-0">Comprehensive logging and error tracking system</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.logs.dashboard') }}" class="btn btn-light btn-lg">
                                    <i class="fas fa-tachometer-alt me-2"></i> Open Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- QA Status Overview -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-check me-2"></i>QA Status Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ App\Models\Inspection::where('qa_status', 'pending_qa')->count() }}</h4>
                                        <small>Pending QA</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ App\Models\Inspection::where('qa_status', 'under_qa_review')->count() }}</h4>
                                        <small>Under Review</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ App\Models\Inspection::where('qa_status', 'qa_approved')->count() }}</h4>
                                        <small>Approved</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ App\Models\Inspection::where('qa_status', 'qa_rejected')->count() }}</h4>
                                        <small>Rejected</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ App\Models\Inspection::where('qa_status', 'revision_required')->count() }}</h4>
                                        <small>Needs Revision</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ App\Models\Inspection::whereNotNull('qa_status')->count() }}</h4>
                                        <small>Total QA</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @include('components.notifications', ['limit' => 10])

        <!-- Recent QA Activities -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bell me-2"></i>Recent QA Activities
                        </h5>
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View All
                        </a>
                    </div>
                    <div class="card-body">
                        @php
                            $recentQAActivities = App\Models\Inspection::with(['client', 'qaReviewer'])
                                ->whereNotNull('qa_reviewed_at')
                                ->orderBy('qa_reviewed_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @if($recentQAActivities->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentQAActivities as $activity)
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">
                                                <span class="badge bg-{{ $activity->qa_status === 'qa_approved' ? 'success' : ($activity->qa_status === 'qa_rejected' ? 'danger' : 'warning') }} me-2">
                                                    {{ $activity->qa_status === 'qa_approved' ? 'APPROVED' : ($activity->qa_status === 'qa_rejected' ? 'REJECTED' : 'REVISION REQUIRED') }}
                                                </span>
                                                {{ $activity->inspection_number }}
                                            </div>
                                            <small class="text-muted">
                                                Client: {{ $activity->client->client_name ?? 'Unknown' }} | 
                                                Reviewed by: {{ $activity->qaReviewer->name ?? 'Unknown' }} |
                                                @php
                                                    $reviewedAt = $activity->qa_reviewed_at;
                                                    if (is_string($reviewedAt)) {
                                                        $reviewedAt = \Carbon\Carbon::parse($reviewedAt);
                                                    }
                                                @endphp
                                                @if($reviewedAt && $reviewedAt instanceof \Carbon\Carbon)
                                                    {{ $reviewedAt->diffForHumans() }}
                                                @else
                                                    {{ $activity->qa_reviewed_at ?? 'Unknown time' }}
                                                @endif
                                            </small>
                                        </div>
                                        <a href="{{ route('inspections.show', $activity->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No recent QA activities found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                            <a href="{{ route('users.create') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-2"></i>Add New User
                            </a>
                            <a href="{{ route('inspections.index') }}" class="btn btn-outline-info">
                                <i class="fas fa-list me-2"></i>View All Inspections
                            </a>
                            <a href="{{ route('inspections.create') }}" class="btn btn-outline-warning">
                                <i class="fas fa-plus me-2"></i>New Inspection
                            </a>
                            @if(Auth::user()->canApproveInspections())
                                <a href="{{ route('qa.pending') }}" class="btn btn-outline-success">
                                    <i class="fas fa-clipboard-check me-2"></i>QA Review Queue
                                </a>
                            @endif
                            @if(Auth::user()->isSuperAdmin())
                                <hr class="my-3">
                                <small class="text-muted fw-bold">SUPER ADMIN RESOURCES</small>
                                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-building me-2"></i>Manage Clients
                                </a>
                                <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-tie me-2"></i>Manage Personnel
                                </a>
                                <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-tools me-2"></i>Manage Equipment
                                </a>
                                <a href="{{ route('admin.consumables.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-flask me-2"></i>Manage Consumables
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>Recent Users
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentUsers as $user)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <span class="badge bg-{{ $user->role_color }}">{{ $user->role_name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No recent users found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Regular User Dashboard -->
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>My Recent Inspections
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Your recent inspection reports will appear here.</p>
                        <a href="{{ route('inspections.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New Inspection
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Profile Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Role:</strong> 
                            <span class="badge bg-{{ Auth::user()->role_color }}">{{ Auth::user()->role_name }}</span>
                        </p>
                        @if(Auth::user()->department)
                            <p><strong>Department:</strong> {{ Auth::user()->department }}</p>
                        @endif
                        @if(Auth::user()->certification)
                            <p><strong>Certification:</strong> {{ Auth::user()->certification }}</p>
                        @endif
                        @if(Auth::user()->certification_expiry)
                            <p><strong>Cert. Expiry:</strong> 
                                {{ Auth::user()->certification_expiry->format('d/m/Y') }}
                                @if(Auth::user()->is_certification_expiring)
                                    <span class="badge bg-warning">Expiring Soon</span>
                                @elseif(Auth::user()->is_certification_expired)
                                    <span class="badge bg-danger">Expired</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
