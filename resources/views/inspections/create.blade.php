@extends('layouts.master')

@section('title', 'Lifting Inspection Report - Professional Inspection Services')

@php
    $showProgress = true;
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="form-container">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @auth
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-user me-2"></i>
                        Logged in as: {{ auth()->user()->name }} ({{ auth()->user()->email }})
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @else
                    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Warning: You are not logged in. Please <a href="{{ route('login') }}">login</a> to submit inspections.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endauth

                <form id="liftingInspectionForm" method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    
                    @include('inspections.sections.client-information', ['inspection' => null])
                    
                    @include('inspections.sections.add-service')
                    
                    @include('inspections.sections.lifting-examination')
                    
                    @include('inspections.sections.load-test')
                    
                    @include('inspections.sections.mpi-service')
                    
                    @include('inspections.sections.thorough-examination')
                    
                    @include('inspections.sections.visual')
                    
                    @include('inspections.sections.equipment-details')
                    
                    @include('inspections.sections.asset-details')
                    
                    @include('inspections.sections.items-table')
                    
                    @include('inspections.sections.consumables')
                    
                    @include('inspections.sections.comments')
                    
                    @include('inspections.sections.image-upload')
                    
                    @include('inspections.sections.export-section')

                </form>
            </div>
        </div>
    </div>
</div>

@include('inspections.modals.add-service-modal')
@include('inspections.modals.lifting-examination-modal')
@include('inspections.modals.mpi-service-modal')
@include('inspections.modals.visual-inspection-modal')
@include('inspections.modals.load-test-modal')
@include('inspections.modals.thorough-examination-modal')
@include('inspections.modals.ultrasonic-test-modal')
@include('inspections.modals.confirmation-modal')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auto-save.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/inspection-form-simple.js') }}"></script>
@endpush
