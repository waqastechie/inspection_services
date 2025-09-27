@extends('layouts.master')

@section('title', 'My Inspection Reports')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">QA-Approved Inspection Reports</h2>
    <div class="card">
        <div class="card-body">
            @if($inspections->count())
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Inspection Number</th>
                            <th>Project Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspections as $inspection)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $inspection->inspection_number }}</td>
                                <td>{{ $inspection->project_name }}</td>
                                <td>{{ $inspection->inspection_date ? $inspection->inspection_date->format('d/m/Y') : '-' }}</td>
                                <td><span class="badge bg-success">QA Approved</span></td>
                                <td>
                                    <a href="{{ route('client.inspections.show', $inspection->id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $inspections->links() }}
            @else
                <div class="alert alert-info mb-0">No QA-approved inspections available.</div>
            @endif
        </div>
    </div>
</div>
@endsection
