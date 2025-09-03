@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Equipment Management</h2>
                <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Equipment
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
                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-3" id="equipmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="assets-tab" data-bs-toggle="tab" data-bs-target="#assets" type="button" role="tab">
                                <i class="fas fa-boxes"></i> Assets
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab">
                                <i class="fas fa-cogs"></i> All Items
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="hierarchy-tab" data-bs-toggle="tab" data-bs-target="#hierarchy" type="button" role="tab">
                                <i class="fas fa-sitemap"></i> Hierarchy View
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="equipmentTabContent">
                        <!-- Assets Tab -->
                        <div class="tab-pane fade show active" id="assets" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Asset Name</th>
                                            <th>Type</th>
                                            <th>Serial Number</th>
                                            <th>Items Count</th>
                                            <th>Condition</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($equipment->where('equipment_category', 'asset') as $asset)
                                            <tr>
                                                <td>
                                                    <strong>{{ $asset->name }}</strong>
                                                    @if($asset->reason_for_examination)
                                                        <br><small class="text-muted">{{ Str::limit($asset->reason_for_examination, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $asset->type }}</td>
                                                <td>{{ $asset->serial_number ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $asset->items->count() }} items</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $asset->condition_color }}">
                                                        {{ ucfirst($asset->condition) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $asset->status_color }}">
                                                        {{ $asset->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.equipment.show', $asset) }}" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.equipment.edit', $asset) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.equipment.toggle-status', $asset) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-{{ $asset->is_active ? 'warning' : 'success' }}">
                                                                <i class="fas fa-{{ $asset->is_active ? 'pause' : 'play' }}"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No assets found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Items Tab -->
                        <div class="tab-pane fade" id="items" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Parent Asset</th>
                                            <th>Type</th>
                                            <th>Serial Number</th>
                                            <th>SWL</th>
                                            <th>Examination Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($equipment->where('equipment_category', 'item') as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @if($item->parentEquipment)
                                                        <a href="{{ route('admin.equipment.show', $item->parentEquipment) }}" class="text-decoration-none">
                                                            {{ $item->parentEquipment->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Standalone</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->type }}</td>
                                                <td>{{ $item->serial_number ?? '-' }}</td>
                                                <td>{{ $item->swl ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $item->examination_status_color }}">
                                                        {{ $item->examination_status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.equipment.show', $item) }}" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.equipment.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No items found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Hierarchy Tab -->
                        <div class="tab-pane fade" id="hierarchy" role="tabpanel">
                            @forelse($equipment->where('equipment_category', 'asset') as $asset)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-box text-primary"></i>
                                                {{ $asset->name }} 
                                                <span class="badge bg-secondary">{{ $asset->serial_number }}</span>
                                            </h5>
                                            <div>
                                                <span class="badge bg-{{ $asset->status_color }}">{{ $asset->status_text }}</span>
                                                <a href="{{ route('admin.equipment.show', $asset) }}" class="btn btn-sm btn-outline-primary ms-2">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($asset->items->count() > 0)
                                            <div class="row">
                                                @foreach($asset->items as $item)
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <div class="card border-start border-info border-3">
                                                            <div class="card-body py-2">
                                                                <h6 class="card-title mb-1">
                                                                    <i class="fas fa-cog text-info"></i>
                                                                    {{ $item->name }}
                                                                </h6>
                                                                <div class="small text-muted">
                                                                    <div><strong>S/N:</strong> {{ $item->serial_number ?? 'N/A' }}</div>
                                                                    <div><strong>SWL:</strong> {{ $item->swl ?? 'N/A' }}</div>
                                                                    <div><strong>Status:</strong> 
                                                                        <span class="badge bg-{{ $item->examination_status_color }} ms-1">
                                                                            {{ $item->examination_status }}
                                                                        </span>
                                                                    </div>
                                                                    @if($item->next_examination_date)
                                                                        <div><strong>Next Exam:</strong> {{ $item->next_examination_date->format('M d, Y') }}</div>
                                                                    @endif
                                                                </div>
                                                                <div class="mt-2">
                                                                    <a href="{{ route('admin.equipment.show', $item) }}" class="btn btn-xs btn-outline-info">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('admin.equipment.edit', $item) }}" class="btn btn-xs btn-outline-primary">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                This asset has no items assigned to it.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    No assets found to display hierarchy.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($equipment->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $equipment->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.675rem;
    line-height: 1.25;
    border-radius: 0.2rem;
}
</style>
