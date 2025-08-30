@extends('layouts.app')

@section('title', 'System Logs')

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
    
    .log-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .log-level {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .level-error {
        background-color: #ff5252;
        color: white;
    }
    
    .level-warning {
        background-color: #ff9800;
        color: white;
    }
    
    .level-info {
        background-color: #2196f3;
        color: white;
    }
    
    .level-debug {
        background-color: #9e9e9e;
        color: white;
    }
    
    .log-row {
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    
    .log-row:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .log-message {
        max-width: 300px;
        word-wrap: break-word;
    }
    
    .status-resolved {
        color: #4caf50;
        font-weight: bold;
    }
    
    .status-unresolved {
        color: #f44336;
        font-weight: bold;
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
                        <i class="fas fa-exclamation-triangle me-3"></i>
                        System Logs
                    </h1>
                    <p class="lead mb-0">Monitor and manage system errors and warnings</p>
                </div>
                <div>
                    <a href="{{ route('admin.logs.dashboard') }}" class="btn btn-glass me-2">
                        <i class="fas fa-chart-line me-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.logs.export.system') }}" class="btn btn-glass">
                        <i class="fas fa-download me-2"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.logs.system') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-white">Level</label>
                        <select name="level" class="form-select">
                            <option value="">All Levels</option>
                            <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>Error</option>
                            <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="debug" {{ request('level') == 'debug' ? 'selected' : '' }}>Debug</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($logTypes ?? [] as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="unresolved" {{ request('status') == 'unresolved' ? 'selected' : '' }}>Unresolved</option>
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
                               placeholder="Search messages..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-glass me-2">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.logs.system') }}" class="btn btn-glass">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="logs-card">
            <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        System Logs ({{ $logs->total() ?? 0 }} total)
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                @if(($logs ?? collect())->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Level</th>
                                <th>Type</th>
                                <th>Message</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            <tr class="log-row" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                <td>
                                    <span class="log-level level-{{ strtolower($log->level) }}">
                                        {{ $log->level }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $log->type }}</span>
                                </td>
                                <td class="log-message">
                                    {{ Str::limit($log->message, 50) }}
                                </td>
                                <td>
                                    {{ $log->user->name ?? 'System' }}
                                </td>
                                <td>
                                    <code>{{ $log->ip_address }}</code>
                                </td>
                                <td>
                                    <small>{{ $log->created_at->format('M j, Y H:i') }}</small>
                                </td>
                                <td>
                                    @if($log->resolved_at)
                                    <span class="status-resolved">
                                        <i class="fas fa-check-circle me-1"></i>Resolved
                                    </span>
                                    @else
                                    <span class="status-unresolved">
                                        <i class="fas fa-exclamation-circle me-1"></i>Unresolved
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(!$log->resolved_at)
                                        <form method="POST" action="{{ route('admin.logs.system.resolve', $log->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="event.stopPropagation()" title="Mark as Resolved">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.logs.system.delete', $log->id) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="event.stopPropagation()" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(method_exists($logs, 'links'))
                <div class="py-3">
                    {{ $logs->appends(request()->query())->links('custom.pagination') }}
                </div>
                @endif
                
                @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>No System Logs Found</h5>
                    <p class="text-muted">No logs match your current filters.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Log Detail Modals -->
@foreach($logs ?? [] as $log)
<div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="log-level level-{{ strtolower($log->level) }} me-2">
                        {{ $log->level }}
                    </span>
                    {{ $log->type }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>User:</strong> {{ $log->user->name ?? 'System' }}
                    </div>
                    <div class="col-md-6">
                        <strong>IP Address:</strong> <code>{{ $log->ip_address }}</code>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date:</strong> {{ $log->created_at->format('M j, Y H:i:s') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        @if($log->resolved_at)
                        <span class="status-resolved">Resolved ({{ $log->resolved_at->format('M j, Y H:i') }})</span>
                        @else
                        <span class="status-unresolved">Unresolved</span>
                        @endif
                    </div>
                </div>
                
                @if($log->url)
                <div class="mb-3">
                    <strong>URL:</strong> <code>{{ $log->url }}</code>
                </div>
                @endif
                
                <div class="mb-3">
                    <strong>Message:</strong>
                    <div class="code-block">{{ $log->message }}</div>
                </div>
                
                @if($log->context)
                <div class="mb-3">
                    <strong>Context:</strong>
                    <div class="code-block">{{ json_encode($log->context, JSON_PRETTY_PRINT) }}</div>
                </div>
                @endif
                
                @if($log->stack_trace)
                <div class="mb-3">
                    <strong>Stack Trace:</strong>
                    <div class="code-block">{{ $log->stack_trace }}</div>
                </div>
                @endif
                
                @if($log->resolved_by_user_id && $log->resolution_notes)
                <div class="mb-3">
                    <strong>Resolution Notes:</strong>
                    <div class="code-block">{{ $log->resolution_notes }}</div>
                    <small class="text-muted">
                        Resolved by: {{ $log->resolvedBy->name ?? 'Unknown' }}
                    </small>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                @if(!$log->resolved_at)
                <form method="POST" action="{{ route('admin.logs.system.resolve', $log->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Mark as Resolved
                    </button>
                </form>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
