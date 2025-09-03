@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Equipment Details</h2>
                <div>
                    <a href="{{ route('admin.equipment.edit', $equipment) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Equipment
                    </a>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left"></i> Back to Equipment
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Name:</strong></div>
                                <div class="col-sm-8">{{ $equipment->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Type:</strong></div>
                                <div class="col-sm-8">{{ $equipment->type }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Brand/Model:</strong></div>
                                <div class="col-sm-8">{{ $equipment->brand_model ?: 'N/A' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Serial Number:</strong></div>
                                <div class="col-sm-8">{{ $equipment->serial_number ?: 'N/A' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Condition:</strong></div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $equipment->condition_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $equipment->condition)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Status:</strong></div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $equipment->status_color }}">
                                        {{ $equipment->status_text }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Usage Hours:</strong></div>
                                <div class="col-sm-8">{{ $equipment->usage_hours ? number_format($equipment->usage_hours, 2) . ' hours' : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calibration Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-certificate"></i> Calibration Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Last Calibration:</strong></div>
                                <div class="col-sm-8">{{ $equipment->calibration_date ? $equipment->calibration_date->format('M d, Y') : 'N/A' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Next Calibration Due:</strong></div>
                                <div class="col-sm-8">
                                    @if($equipment->calibration_due)
                                        {{ $equipment->calibration_due->format('M d, Y') }}
                                        @if($equipment->is_calibration_overdue)
                                            <span class="badge bg-danger ms-2">Overdue</span>
                                        @elseif($equipment->is_calibration_due)
                                            <span class="badge bg-warning ms-2">Due Soon</span>
                                        @else
                                            <span class="badge bg-success ms-2">Current</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Certificate Number:</strong></div>
                                <div class="col-sm-8">{{ $equipment->calibration_certificate ?: 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications and Notes -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs"></i> Specifications
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($equipment->specifications)
                                <div class="specifications-content">
                                    {!! nl2br(e($equipment->specifications)) !!}
                                </div>
                            @else
                                <p class="text-muted">No specifications recorded.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note"></i> Maintenance Notes
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($equipment->maintenance_notes)
                                <div class="notes-content">
                                    {!! nl2br(e($equipment->maintenance_notes)) !!}
                                </div>
                            @else
                                <p class="text-muted">No maintenance notes recorded.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clock"></i> Record Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Created:</strong></div>
                                        <div class="col-sm-8">{{ $equipment->created_at->format('M d, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-sm-4"><strong>Last Updated:</strong></div>
                                        <div class="col-sm-8">{{ $equipment->updated_at->format('M d, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.specifications-content, .notes-content {
    white-space: pre-wrap;
    font-family: monospace;
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #e9ecef;
}
</style>
@endsection
