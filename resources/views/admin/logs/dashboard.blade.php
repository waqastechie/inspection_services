@extends('layouts.app')

@section('title', 'Log Dashboard')

@section('styles')
<style>
    .log-dashboard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(31, 38, 135, 0.4);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: scale(1.05);
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .error-card {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    }
    
    .warning-card {
        background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
    }
    
    .info-card {
        background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%);
    }
    
    .success-card {
        background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
    }
    
    .chart-container {
        height: 300px;
        position: relative;
    }
    
    .recent-logs {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .log-item {
        padding: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.2s ease;
    }
    
    .log-item:hover {
        background-color: rgba(0,0,0,0.05);
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
    
    .page-header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        text-align: center;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        justify-content: center;
    }
    
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="log-dashboard">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="display-4 mb-3">
                <i class="fas fa-chart-line me-3"></i>
                System Monitoring Dashboard
            </h1>
            <p class="lead mb-0">
                Comprehensive logging and error tracking system for inspection services
            </p>
            <div class="action-buttons">
                <a href="{{ route('admin.logs.system') }}" class="btn-glass">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    System Logs
                </a>
                <a href="{{ route('admin.logs.activity') }}" class="btn-glass">
                    <i class="fas fa-users me-2"></i>
                    Activity Logs
                </a>
                <a href="{{ route('admin.logs.export.system') }}" class="btn-glass">
                    <i class="fas fa-download me-2"></i>
                    Export System
                </a>
                <a href="{{ route('admin.logs.export.activity') }}" class="btn-glass">
                    <i class="fas fa-download me-2"></i>
                    Export Activity
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card error-card">
                    <div class="stats-number">{{ $stats['errors'] ?? 0 }}</div>
                    <div class="stats-label">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Total Errors
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card warning-card">
                    <div class="stats-number">{{ $stats['warnings'] ?? 0 }}</div>
                    <div class="stats-label">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Total Warnings
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card info-card">
                    <div class="stats-number">{{ $stats['activities'] ?? 0 }}</div>
                    <div class="stats-label">
                        <i class="fas fa-user-clock me-1"></i>
                        Total Activities
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card success-card">
                    <div class="stats-number">{{ $stats['resolved'] ?? 0 }}</div>
                    <div class="stats-label">
                        <i class="fas fa-check-circle me-1"></i>
                        Issues Resolved
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8 mb-3">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-area me-2 text-primary"></i>
                            System Activity Over Time
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2 text-info"></i>
                            Error Distribution
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="errorChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                            Recent System Errors
                        </h5>
                        <a href="{{ route('admin.logs.system') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="recent-logs">
                            @if(isset($recentErrors) && $recentErrors->count() > 0)
                                @foreach($recentErrors as $log)
                                <div class="log-item">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="log-level level-{{ strtolower($log->level) }}">
                                                {{ $log->level }}
                                            </span>
                                            <small class="text-muted ms-2">
                                                {{ $log->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="fw-bold">{{ $log->type }}</div>
                                        <div class="text-muted small">
                                            {{ Str::limit($log->message, 60) }}
                                        </div>
                                    </div>
                                    @if(!$log->resolved_at)
                                    <div class="text-end">
                                        <span class="badge bg-danger">Unresolved</span>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <div>No recent errors found</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2 text-success"></i>
                            Recent User Activities
                        </h5>
                        <a href="{{ route('admin.logs.activity') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="recent-logs">
                            @if(isset($recentActivities) && $recentActivities->count() > 0)
                                @foreach($recentActivities as $activity)
                                <div class="log-item">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <strong>{{ $activity->user->name ?? 'System' }}</strong>
                                            <small class="text-muted ms-2">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="text-primary">{{ $activity->action }}</div>
                                        @if($activity->model_type && $activity->model_id)
                                        <div class="text-muted small">
                                            {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <div>No recent activities found</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['dates'] ?? []) !!},
            datasets: [{
                label: 'System Errors',
                data: {!! json_encode($chartData['errors'] ?? []) !!},
                borderColor: '#ff6b6b',
                backgroundColor: 'rgba(255, 107, 107, 0.1)',
                tension: 0.4
            }, {
                label: 'User Activities',
                data: {!! json_encode($chartData['activities'] ?? []) !!},
                borderColor: '#4caf50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Error Distribution Chart
    const errorCtx = document.getElementById('errorChart').getContext('2d');
    new Chart(errorCtx, {
        type: 'doughnut',
        data: {
            labels: ['Errors', 'Warnings', 'Info', 'Debug'],
            datasets: [{
                data: {!! json_encode($chartData['distribution'] ?? [0, 0, 0, 0]) !!},
                backgroundColor: [
                    '#ff6b6b',
                    '#ffa726',
                    '#42a5f5',
                    '#9e9e9e'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
