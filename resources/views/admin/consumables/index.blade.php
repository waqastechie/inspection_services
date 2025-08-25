@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Consumables Management</h2>
                <a href="{{ route('admin.consumables.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Consumable
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Brand/Manufacturer</th>
                                    <th>Quantity</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($consumables as $consumable)
                                    <tr>
                                        <td>{{ $consumable->name }}</td>
                                        <td>{{ $consumable->type }}</td>
                                        <td>{{ $consumable->brand_manufacturer ?? '-' }}</td>
                                        <td>{{ $consumable->quantity_available }} {{ $consumable->unit ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $consumable->condition === 'new' ? 'success' : 
                                                ($consumable->condition === 'good' ? 'primary' : 
                                                ($consumable->condition === 'expired' ? 'warning' : 'danger')) 
                                            }}">
                                                {{ ucfirst($consumable->condition) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $consumable->is_active ? 'success' : 'danger' }}">
                                                {{ $consumable->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.consumables.show', $consumable) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.consumables.edit', $consumable) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.consumables.toggle-status', $consumable) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $consumable->is_active ? 'warning' : 'success' }}">
                                                        <i class="fas fa-{{ $consumable->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $consumable->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $consumable->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete {{ $consumable->name }}?
                                                    This action cannot be undone.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.consumables.destroy', $consumable) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No consumables found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($consumables->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $consumables->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
