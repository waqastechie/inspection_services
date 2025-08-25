@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Personnel Details: {{ $personnel->first_name }} {{ $personnel->last_name }}</h2>
                <div>
                    <a href="{{ route('admin.personnel.edit', $personnel) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.personnel.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Personnel
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Employee ID:</strong></div>
                                <div class="col-sm-8">{{ $personnel->employee_id ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Name:</strong></div>
                                <div class="col-sm-8">{{ $personnel->first_name }} {{ $personnel->last_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Position:</strong></div>
                                <div class="col-sm-8">{{ $personnel->position }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Department:</strong></div>
                                <div class="col-sm-8">{{ $personnel->department }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Supervisor:</strong></div>
                                <div class="col-sm-8">{{ $personnel->supervisor ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Hire Date:</strong></div>
                                <div class="col-sm-8">{{ $personnel->hire_date?->format('M d, Y') ?? '-' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Status:</strong></div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $personnel->is_active ? 'success' : 'danger' }}">
                                        {{ $personnel->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Email:</strong></div>
                                <div class="col-sm-8">
                                    @if($personnel->email)
                                        <a href="mailto:{{ $personnel->email }}">{{ $personnel->email }}</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Phone:</strong></div>
                                <div class="col-sm-8">
                                    @if($personnel->phone)
                                        <a href="tel:{{ $personnel->phone }}">{{ $personnel->phone }}</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Address:</strong></div>
                                <div class="col-sm-8">{{ $personnel->address ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>City:</strong></div>
                                <div class="col-sm-8">{{ $personnel->city ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>State:</strong></div>
                                <div class="col-sm-8">{{ $personnel->state ?? '-' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>ZIP Code:</strong></div>
                                <div class="col-sm-8">{{ $personnel->zip_code ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Professional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Experience:</strong></div>
                                <div class="col-sm-8">{{ $personnel->experience_years ?? '-' }} years</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Qualifications:</strong></div>
                                <div class="col-sm-8">{!! nl2br(e($personnel->qualifications ?? '-')) !!}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Certifications:</strong></div>
                                <div class="col-sm-8">{!! nl2br(e($personnel->certifications ?? '-')) !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Notes:</strong></div>
                                <div class="col-sm-8">{!! nl2br(e($personnel->notes ?? '-')) !!}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Created:</strong></div>
                                <div class="col-sm-8">{{ $personnel->created_at->format('M d, Y \a\t g:i A') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><strong>Last Updated:</strong></div>
                                <div class="col-sm-8">{{ $personnel->updated_at->format('M d, Y \a\t g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
