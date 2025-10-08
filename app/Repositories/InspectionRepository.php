<?php

namespace App\Repositories;

use App\Models\Inspection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InspectionRepository
{
    /**
     * Get all inspections with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Inspection::with(['client', 'equipmentType'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get active inspections
     *
     * @return Collection
     */
    public function getActive(): Collection
    {
        return Inspection::where('status', 'active')
            ->with(['client', 'equipmentType'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find inspection by ID
     *
     * @param int $id
     * @return Inspection|null
     */
    public function findById(int $id): ?Inspection
    {
        return Inspection::with([
            'client',
            'services',
            'personnelAssignments',
            'equipmentAssignments.equipment',
            'consumableAssignments',
            'inspectionResults',
            'images',
            'liftingExamination',
            'mpiInspection',
            'loadTest',
            'otherTest',
            'inspectionEquipment',
            'equipmentType'
        ])->find($id);
    }

    /**
     * Create a new inspection
     *
     * @param array $data
     * @return Inspection
     */
    public function create(array $data): Inspection
    {
        return Inspection::create($data);
    }

    /**
     * Update an inspection
     *
     * @param int $id
     * @param array $data
     * @return Inspection|null
     */
    public function update(int $id, array $data): ?Inspection
    {
        $inspection = $this->findById($id);
        
        if (!$inspection) {
            return null;
        }
        
        $inspection->update($data);
        return $inspection;
    }

    /**
     * Delete an inspection
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $inspection = $this->findById($id);
        
        if (!$inspection) {
            return false;
        }
        
        return $inspection->delete();
    }

    /**
     * Get inspections due for renewal
     *
     * @param int $daysThreshold
     * @return Collection
     */
    public function getDueForRenewal(int $daysThreshold = 30): Collection
    {
        $date = now()->addDays($daysThreshold);
        
        return Inspection::whereNotNull('next_inspection_date')
            ->whereDate('next_inspection_date', '<=', $date)
            ->with(['client', 'equipmentType'])
            ->orderBy('next_inspection_date')
            ->get();
    }
}