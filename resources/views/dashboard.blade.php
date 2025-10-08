@extends('layouts.app')

@section('title', 'Dashboard - Inspection Services')

@push('styles')
<link href="{{ asset('css/modern-dashboard.css') }}" rel="stylesheet">
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
<div class="modern-dashboard">
<div class="container-fluid p-4">
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
            <!-- Modern Stats Cards -->
            <div class="col-md-3">
                <div class="stats-card primary">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-card-title">Total Users</div>
                        <div class="stats-card-value">{{ App\Models\User::count() }}</div>
                        <div class="stats-card-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>Active system users</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stats-card-title">Active Clients</div>
                        <div class="stats-card-value">{{ App\Models\Client::active()->count() }}</div>
                        <div class="stats-card-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>Engaged clients</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stats-card-title">Personnel</div>
                        <div class="stats-card-value">{{ App\Models\Personnel::active()->count() }}</div>
                        <div class="stats-card-trend positive">
                            <i class="fas fa-check-circle"></i>
                            <span>Certified staff</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stats-card-title">Total Inspections</div>
                        <div class="stats-card-value">{{ App\Models\Inspection::count() }}</div>
                        <div class="stats-card-trend positive">
                            <i class="fas fa-chart-line"></i>
                            <span>Completed reports</span>
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
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>QA Status Overview
                        </h5>
                    </div>
                    <div class="modern-card-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <div class="qa-status-card pending">
                                    <div class="qa-status-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="qa-status-value">{{ App\Models\Inspection::where('qa_status', 'pending_qa')->count() }}</div>
                                    <div class="qa-status-label">Pending QA</div>
                                    <div class="qa-status-indicator"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="qa-status-card under-review">
                                    <div class="qa-status-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="qa-status-value">{{ App\Models\Inspection::where('qa_status', 'under_qa_review')->count() }}</div>
                                    <div class="qa-status-label">Under Review</div>
                                    <div class="qa-status-indicator"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="qa-status-card approved">
                                    <div class="qa-status-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="qa-status-value">{{ App\Models\Inspection::where('qa_status', 'qa_approved')->count() }}</div>
                                    <div class="qa-status-label">Approved</div>
                                    <div class="qa-status-indicator"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="qa-status-card rejected">
                                    <div class="qa-status-icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="qa-status-value">{{ App\Models\Inspection::where('qa_status', 'qa_rejected')->count() }}</div>
                                    <div class="qa-status-label">Rejected</div>
                                    <div class="qa-status-indicator"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="qa-status-card needs-revision">
                                    <div class="qa-status-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="qa-status-value">{{ App\Models\Inspection::where('qa_status', 'revision_required')->count() }}</div>
                                    <div class="qa-status-label">Needs Revision</div>
                                    <div class="qa-status-indicator"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="qa-status-card total">
                                    <div class="qa-status-icon">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <div class="qa-status-value">{{ App\Models\Inspection::whereNotNull('qa_status')->count() }}</div>
                                    <div class="qa-status-label">Total QA</div>
                                    <div class="qa-status-indicator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications and Recent Activities -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>
                            Notifications
                        </h5>
                    </div>
                    <div class="modern-card-body">
                        @include('components.notifications', ['limit' => 5])
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Recent QA Activities
                        </h5>
                    </div>
                    <div class="modern-card-body">
                        @php
                            $recentQAActivities = App\Models\Inspection::with(['client', 'qaReviewer'])
                                ->whereNotNull('qa_reviewed_at')
                                ->orderBy('qa_reviewed_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @if($recentQAActivities->count() > 0)
                            <div class="activity-list">
                                @foreach($recentQAActivities as $activity)
                                    <div class="activity-item">
                                        <div class="activity-status-indicator {{ $activity->qa_status == 'qa_approved' ? 'approved' : ($activity->qa_status == 'qa_rejected' ? 'rejected' : 'pending') }}"></div>
                                        <div class="activity-content">
                                            <div class="activity-title">{{ $activity->inspection_number }}</div>
                                            <div class="activity-client">{{ $activity->client->client_name ?? 'N/A' }}</div>
                                            <div class="activity-status">
                                                <span class="status-badge {{ $activity->qa_status == 'qa_approved' ? 'approved' : ($activity->qa_status == 'qa_rejected' ? 'rejected' : 'pending') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $activity->qa_status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="activity-meta">
                                            <div class="activity-reviewer">{{ $activity->qaReviewer->name ?? 'N/A' }}</div>
                                            <div class="activity-time">
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <p>No recent QA activities</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>



        <!-- Recent Users -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Recent Users
                        </h5>
                    </div>
                    <div class="modern-card-body">
                        @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="user-list">
                                @foreach($recentUsers as $user)
                                    <div class="user-item">
                                        <div class="user-avatar">
                                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="user-info">
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                            <div class="user-role">
                                                <span class="role-badge {{ $user->role_color }}">{{ $user->role_name }}</span>
                                            </div>
                                        </div>
                                        <div class="user-status">
                                            <span class="status-indicator active"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>No recent users found</p>
                            </div>
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
                        @if(Auth::user()->role_name === 'Quality Assurance')
        @php
            $qaInspections = App\Models\Inspection::where('qa_reviewer_id', Auth::id())
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        @endphp
        @if($qaInspections->count() > 0)
            <ul class="list-group list-group-flush mb-3">
                @foreach($qaInspections as $inspection)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $inspection->inspection_number }}</strong>
                            <span class="text-muted small">{{ $inspection->client->client_name ?? 'Unknown Client' }}</span>
                        </div>
                        <a href="{{ route('qa.review', $inspection) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-eye"></i> Review
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted mb-0">No inspections assigned to you for QA review.</p>
        @endif
        <a href="{{ route('qa.pending') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create New Inspection
        </a>
    @else
        <p class="text-muted">Your recent inspection reports will appear here.</p>
        <a href="{{ route('inspections.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create New Inspection
        </a>
    @endif
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
