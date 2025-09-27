@extends('layouts.app')

@section('title', 'QA Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-shield-check me-2 text-success"></i>
                        Quality Assurance Dashboard
                    </h1>
                    <p class="text-muted mb-0">Monitor and review inspection reports</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary">{{ auth()->user()->role_name }}</span>
                    <span class="badge bg-info">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- QA Metrics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0 fw-bold">Pending QA</h6>
                            <h2 class="mb-0">{{ $pendingQA }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0 fw-bold">Under Review</h6>
                            <h2 class="mb-0">{{ $underReview }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-eye fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0 fw-bold">Approved</h6>
                            <h2 class="mb-0">{{ $approved }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0 fw-bold">Rejected</h6>
                            <h2 class="mb-0">{{ $rejected }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0 fw-bold">Needs Revision</h6>
                            <h2 class="mb-0">{{ $requiresRevision }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-edit fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0 fw-bold">Total Reviews</h6>
                            <h2 class="mb-0">{{ $approved + $rejected + $requiresRevision }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-bar fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('qa.pending') }}" class="btn btn-warning w-100">
                                <i class="fas fa-clock me-2"></i>Review Pending ({{ $pendingQA }})
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('qa.under-review') }}" class="btn btn-info w-100">
                                <i class="fas fa-eye me-2"></i>Under Review ({{ $underReview }})
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('qa.history') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-history me-2"></i>Review History
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('inspections.index') }}" class="btn btn-primary w-100">
                                <i class="fas fa-clipboard-list me-2"></i>All Inspections
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <!-- Pending Inspections -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Pending QA Review
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($pendingInspections->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($pendingInspections as $inspection)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $inspection->inspection_number }}</h6>
                                            <p class="mb-1 text-muted small">{{ $inspection->client->client_name ?? 'Unknown Client' }}</p>
                                            <small class="text-muted">{{ $inspection->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            <a href="{{ route('qa.review', $inspection) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($pendingInspections->count() >= 10)
                            <div class="card-footer text-center">
                                <a href="{{ route('qa.pending') }}" class="btn btn-sm btn-warning">
                                    View All Pending
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No pending inspections</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Under Review -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Under Your Review
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($underReviewInspections->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($underReviewInspections as $inspection)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $inspection->inspection_number }}</h6>
                                            <p class="mb-1 text-muted small">{{ $inspection->client->client_name ?? 'Unknown Client' }}</p>
                                            <small class="text-muted">Started {{ $inspection->updated_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            <a href="{{ route('qa.review', $inspection) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($underReviewInspections->count() >= 10)
                            <div class="card-footer text-center">
                                <a href="{{ route('qa.under-review') }}" class="btn btn-sm btn-info">
                                    View All Under Review
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No inspections under review</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recently Reviewed -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recently Reviewed
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($recentlyReviewed->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentlyReviewed as $inspection)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $inspection->inspection_number }}</h6>
                                            <p class="mb-1 text-muted small">{{ $inspection->client->client_name ?? 'Unknown Client' }}</p>
                                            <small class="text-muted">{{ $inspection->qa_reviewed_at?->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-{{ $inspection->qa_status_color }}">
                                                {{ str_replace('_', ' ', ucfirst($inspection->qa_status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($recentlyReviewed->count() >= 10)
                            <div class="card-footer text-center">
                                <a href="{{ route('qa.history') }}" class="btn btn-sm btn-secondary">
                                    View All History
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent reviews</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush