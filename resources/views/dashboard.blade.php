@extends('layouts.master')

@section('title', 'Dashboard - Inspection Services')

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
                <div>
                    <span class="badge bg-{{ Auth::user()->role_color }} fs-6 me-3">
                        {{ Auth::user()->role_name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
        <!-- Admin Dashboard -->
        <div class="row g-4 mb-4">
            <!-- Stats Cards -->
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Users</h6>
                                <h2 class="mb-0">{{ $totalUsers ?? 0 }}</h2>
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
