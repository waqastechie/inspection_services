@extends('layouts.master')

@section('title', 'Inspection Report Details')

@section('content')
<div class="container mt-4">
    <a href="{{ route('client.inspections.index') }}" class="btn btn-outline-secondary mb-3">&larr; Back to Reports</a>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Inspection #{{ $inspection->inspection_number }}</h4>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Project Name</dt>
                <dd class="col-sm-9">{{ $inspection->project_name }}</dd>
                <dt class="col-sm-3">Inspection Date</dt>
                <dd class="col-sm-9">{{ $inspection->inspection_date ? $inspection->inspection_date->format('d/m/Y') : '-' }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge bg-success">QA Approved</span></dd>
                <dt class="col-sm-3">Lead Inspector</dt>
                <dd class="col-sm-9">{{ $inspection->lead_inspector_name }}</dd>
                <dt class="col-sm-3">General Notes</dt>
                <dd class="col-sm-9">{{ $inspection->general_notes }}</dd>
                <dt class="col-sm-3">QA Comments</dt>
                <dd class="col-sm-9">{{ $inspection->qa_comments }}</dd>
            </dl>
            <hr>
            <h5>Report Details</h5>
            <div>
                {!! nl2br(e($inspection->inspector_comments)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
