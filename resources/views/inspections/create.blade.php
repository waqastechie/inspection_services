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

                <form id="liftingInspectionForm" method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    
                    @include('inspections.sections.client-information', ['inspection' => null])
                    
                    @include('inspections.sections.add-service')
                    
                    @include('inspections.sections.lifting-examination')
                    
                    @include('inspections.sections.load-test')
                    
                    @include('inspections.sections.mpi-service')
                    
                    @include('inspections.sections.thorough-examination')
                    
                    @include('inspections.sections.visual')
                    
                    @include('inspections.sections.equipment')
                    
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
@include('inspections.modals.confirmation-modal')

<!-- New Modal-Based Entry Modals -->
@include('inspections.modals.asset-modal')
@include('inspections.modals.item-modal')
@include('inspections.modals.equipment-new-modal')
@include('inspections.modals.consumable-new-modal')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auto-save.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/inspection-form.js') }}"></script>
@endpush
