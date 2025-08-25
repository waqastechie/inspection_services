@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">User Details: {{ $user->name }}</h1>
                <div>
                    @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- User Information Card -->
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                            <div class="d-flex align-items-center">
                                @if($user->is_active)
                                    <span class="badge bg-success me-2">Active</span>
                                @else
                                    <span class="badge bg-danger me-2">Inactive</span>
                                @endif
                                <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Full Name</label>
                                        <p class="mb-0">{{ $user->name }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Email Address</label>
                                        <p class="mb-0">
                                            {{ $user->email }}
                                            @if($user->email_verified_at)
                                                <i class="fas fa-check-circle text-success ms-2" title="Email Verified"></i>
                                            @else
                                                <i class="fas fa-exclamation-triangle text-warning ms-2" title="Email Not Verified"></i>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Role</label>
                                        <p class="mb-0">
                                            <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}">
                                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Phone Number</label>
                                        <p class="mb-0">{{ $user->phone ?: 'Not provided' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Department</label>
                                        <p class="mb-0">{{ $user->department ?: 'Not specified' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Certification</label>
                                        <p class="mb-0">{{ $user->certification ?: 'None' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Certification Expiry</label>
                                        <p class="mb-0">
                                            @if($user->certification_expiry)
                                                {{ $user->certification_expiry->format('M d, Y') }}
                                                @if($user->certification_expiry->isPast())
                                                    <span class="badge bg-danger ms-2">Expired</span>
                                                @elseif($user->certification_expiry->diffInDays() <= 30)
                                                    <span class="badge bg-warning ms-2">Expires Soon</span>
                                                @endif
                                            @else
                                                No expiry date
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Status</label>
                                        <p class="mb-0">
                                            @if($user->is_active)
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Active
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    <i class="fas fa-times-circle"></i> Inactive
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary me-2 mb-2">
                                            <i class="fas fa-edit"></i> Edit User
                                        </a>
                                    @endif

                                    @if(!$user->isSuperAdmin())
                                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} me-2 mb-2">
                                                <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                            </button>
                                        </form>
                                    @endif

                                    @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                        <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete User
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Account Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">Created</small>
                                <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">Last Updated</small>
                                <strong>{{ $user->updated_at->format('M d, Y') }}</strong>
                                <br>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>

                            @if($user->email_verified_at)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Email Verified</small>
                                    <strong class="text-success">{{ $user->email_verified_at->format('M d, Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $user->email_verified_at->diffForHumans() }}</small>
                                </div>
                            @endif

                            <hr>

                            <div class="text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <i class="fas fa-{{ $user->role === 'admin' || $user->role === 'super_admin' ? 'user-shield' : ($user->role === 'inspector' ? 'search' : 'eye') }} fa-2x text-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}"></i>
                                        </div>
                                        <strong class="text-muted">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-list"></i> View All Users
                                </a>
                                <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus"></i> Create New User
                                </a>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if(!$user->isSuperAdmin() && $user->id !== auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Delete User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $user->name }}</strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-warning"></i> 
                    This action cannot be undone. All data associated with this user will be permanently removed.
                </p>
                <div class="border-start border-warning ps-3 mt-3">
                    <h6 class="text-warning">User Details:</h6>
                    <ul class="mb-0">
                        <li>Email: {{ $user->email }}</li>
                        <li>Role: {{ ucfirst($user->role) }}</li>
                        <li>Department: {{ $user->department ?: 'Not specified' }}</li>
                        <li>Status: {{ $user->is_active ? 'Active' : 'Inactive' }}</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
