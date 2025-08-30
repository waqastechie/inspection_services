@extends('layouts.app')

@section('title', 'Activity Logs')

@section('styles')
<style>
    .logs-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .logs-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    }
    
    .page-header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .filter-section {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .activity-row {
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    
    .activity-row:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .activity-create {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    }
    
    .activity-update {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    }
    
    .activity-delete {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    }
    
    .activity-view {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    }
    
    .activity-login {
        background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
    }
    
    .activity-logout {
        background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);
    }
    
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        backdrop-filter: blur(10px);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        border-bottom: none;
    }
    
    .code-block {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        white-space: pre-wrap;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 1.5rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 2.5rem;
        width: 2px;
        height: calc(100% - 2rem);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="logs-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 mb-2">
                        <i class="fas fa-user-clock me-3"></i>
                        Activity Logs
                    </h1>
                    <p class="lead mb-0">Track and monitor user activities across the system</p>
                </div>
                <div>
                    <a href="{{ route('admin.logs.dashboard') }}" class="btn btn-glass me-2">
                        <i class="fas fa-chart-line me-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.logs.export.activity') }}" class="btn btn-glass">
                        <i class="fas fa-download me-2"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.logs.activity') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-white">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All Actions</option>
                            @foreach($actions ?? [] as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ $action }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white">Model Type</label>
                        <select name="model_type" class="form-select">
                            <option value="">All Models</option>
                            @foreach($modelTypes ?? [] as $modelType)
                            <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                {{ class_basename($modelType) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white">Date From</label>
                        <input type="date" name="date_from" class="form-select" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search descriptions..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-glass me-2">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.logs.activity') }}" class="btn btn-glass">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Activity Logs -->
        <div class="logs-card">
            <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Activity Timeline ({{ $activities->total() ?? 0 }} total)
                    </h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="timelineView" checked>
                        <label class="form-check-label" for="timelineView">
                            Timeline View
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(($activities ?? collect())->count() > 0)
                
                <!-- Timeline View -->
                <div id="timeline-container">
                    @foreach($activities as $activity)
                    <div class="timeline-item">
                        <div class="d-flex align-items-start">
                            <div class="activity-icon activity-{{ strtolower(explode(' ', $activity->action)[0] ?? 'view') }}">
                                @php
                                    $action = strtolower(explode(' ', $activity->action)[0] ?? '');
                                    $icon = match($action) {
                                        'created', 'create' => 'fas fa-plus',
                                        'updated', 'update' => 'fas fa-edit',
                                        'deleted', 'delete' => 'fas fa-trash',
                                        'login' => 'fas fa-sign-in-alt',
                                        'logout' => 'fas fa-sign-out-alt',
                                        default => 'fas fa-eye'
                                    };
                                @endphp
                                <i class="{{ $icon }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="user-avatar me-2">
                                                {{ substr($activity->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            <strong>{{ $activity->user->name ?? 'System' }}</strong>
                                            <span class="badge bg-primary ms-2">{{ $activity->action }}</span>
                                        </div>
                                        <div class="text-muted mb-2">
                                            {{ $activity->description }}
                                        </div>
                                        @if($activity->model_type && $activity->model_id)
                                        <div class="small text-info">
                                            <i class="fas fa-cube me-1"></i>
                                            {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                                        </div>
                                        @endif
                                        @if($activity->ip_address)
                                        <div class="small text-muted">
                                            <i class="fas fa-globe me-1"></i>
                                            IP: <code>{{ $activity->ip_address }}</code>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                        <div class="small text-muted">
                                            {{ $activity->created_at->format('M j, Y H:i:s') }}
                                        </div>
                                        @if($activity->additional_data)
                                        <button class="btn btn-sm btn-outline-info mt-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#activityModal{{ $activity->id }}">
                                            <i class="fas fa-info-circle"></i> Details
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Table View (Hidden by default) -->
                <div id="table-container" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Model</th>
                                    <th>IP Address</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr class="activity-row">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                {{ substr($activity->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            {{ $activity->user->name ?? 'System' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $activity->action }}</span>
                                    </td>
                                    <td>{{ Str::limit($activity->description, 50) }}</td>
                                    <td>
                                        @if($activity->model_type && $activity->model_id)
                                        <span class="badge bg-secondary">
                                            {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $activity->ip_address }}</code>
                                    </td>
                                    <td>
                                        <small>{{ $activity->created_at->format('M j, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($activity->additional_data)
                                        <button class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#activityModal{{ $activity->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endif
                                        <form method="POST" action="{{ route('admin.logs.activity.delete', $activity->id) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if(method_exists($activities, 'links'))
                <div class="py-3">
                    {{ $activities->appends(request()->query())->links('custom.pagination') }}
                </div>
                @endif
                
                @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5>No Activity Logs Found</h5>
                    <p class="text-muted">No activities match your current filters.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Activity Detail Modals -->
@foreach($activities ?? [] as $activity)
@if($activity->additional_data)
<div class="modal fade" id="activityModal{{ $activity->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="badge bg-light text-dark me-2">{{ $activity->action }}</span>
                    Activity Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>User:</strong> {{ $activity->user->name ?? 'System' }}
                    </div>
                    <div class="col-md-6">
                        <strong>IP Address:</strong> <code>{{ $activity->ip_address }}</code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date:</strong> {{ $activity->created_at->format('M j, Y H:i:s') }}
                    </div>
                    <div class="col-md-6">
                        <strong>User Agent:</strong> 
                        <small>{{ Str::limit($activity->user_agent ?? 'Unknown', 30) }}</small>
                    </div>
                </div>
                
                @if($activity->model_type && $activity->model_id)
                <div class="mb-3">
                    <strong>Model:</strong> 
                    <span class="badge bg-secondary">
                        {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                    </span>
                </div>
                @endif
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="code-block">{{ $activity->description }}</div>
                </div>
                
                @if($activity->additional_data)
                <div class="mb-3">
                    <strong>Properties:</strong>
                    <div class="code-block">{{ json_encode($activity->additional_data, JSON_PRETTY_PRINT) }}</div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timelineToggle = document.getElementById('timelineView');
    const timelineContainer = document.getElementById('timeline-container');
    const tableContainer = document.getElementById('table-container');
    
    timelineToggle.addEventListener('change', function() {
        if (this.checked) {
            timelineContainer.style.display = 'block';
            tableContainer.style.display = 'none';
        } else {
            timelineContainer.style.display = 'none';
            tableContainer.style.display = 'block';
        }
    });
});
</script>
@endsection
