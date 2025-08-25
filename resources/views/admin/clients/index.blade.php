@extends('layouts.master')

@section('title', 'Client Management - Professional Inspection Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-building me-2 text-primary"></i>
                        Client Management
                    </h1>
                    <p class="text-muted mb-0">Manage client information and contacts</p>
                </div>
                <div>
                    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add New Client
                    </a>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Clients</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by name, code, contact...">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Client List ({{ $clients->total() }} total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Code</th>
                                        <th>Industry</th>
                                        <th>Contact Person</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $client->client_name }}</strong>
                                                    @if($client->company_type)
                                                        <br><small class="text-muted">{{ $client->company_type }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($client->client_code)
                                                    <span class="badge bg-secondary">{{ $client->client_code }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $client->industry ?: '-' }}</td>
                                            <td>
                                                @if($client->contact_person)
                                                    <div>
                                                        <strong>{{ $client->contact_person }}</strong>
                                                        @if($client->contact_position)
                                                            <br><small class="text-muted">{{ $client->contact_position }}</small>
                                                        @endif
                                                        @if($client->contact_email)
                                                            <br><small><a href="mailto:{{ $client->contact_email }}">{{ $client->contact_email }}</a></small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($client->city || $client->state)
                                                    {{ $client->city }}{{ $client->city && $client->state ? ', ' : '' }}{{ $client->state }}
                                                    @if($client->country && $client->country !== 'United States')
                                                        <br><small class="text-muted">{{ $client->country }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($client->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.clients.show', $client) }}" 
                                                       class="btn btn-outline-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.clients.edit', $client) }}" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.clients.toggle-status', $client) }}" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-{{ $client->is_active ? 'warning' : 'success' }}" 
                                                                title="{{ $client->is_active ? 'Deactivate' : 'Activate' }}"
                                                                onclick="return confirm('Are you sure you want to {{ $client->is_active ? 'deactivate' : 'activate' }} this client?')">
                                                            <i class="fas fa-{{ $client->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    @if($client->inspections->count() === 0)
                                                        <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" 
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="Delete"
                                                                    onclick="return confirm('Are you sure you want to delete this client? This action cannot be undone.')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $clients->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-building text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h5 class="text-muted mb-3">No Clients Found</h5>
                            <p class="text-muted mb-4">
                                @if(request()->has('search') || request()->has('status'))
                                    No clients match your search criteria.
                                @else
                                    You haven't added any clients yet.
                                @endif
                            </p>
                            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Add First Client
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
