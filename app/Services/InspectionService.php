<?php

namespace App\Services;

use App\Models\Inspection;
use App\Repositories\InspectionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InspectionService
{
    protected $inspectionRepository;

    /**
     * InspectionService constructor
     *
     * @param InspectionRepository $inspectionRepository
     */
    public function __construct(InspectionRepository $inspectionRepository)
    {
        $this->inspectionRepository = $inspectionRepository;
    }

    /**
     * Get all inspections with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllInspections(int $perPage = 15): LengthAwarePaginator
    {
        return $this->inspectionRepository->getAll($perPage);
    }

    /**
     * Get active inspections
     *
     * @return Collection
     */
    public function getActiveInspections(): Collection
    {
        return $this->inspectionRepository->getActive();
    }

    /**
     * Find inspection by ID
     *
     * @param int $id
     * @return Inspection|null
     */
    public function findById(int $id): ?Inspection
    {
        return $this->inspectionRepository->findById($id);
    }

    /**
     * Create a new inspection
     *
     * @param array $data
     * @return Inspection
     */
    public function createInspection(array $data): Inspection
    {
        return $this->inspectionRepository->create($data);
    }

    /**
     * Update an inspection
     *
     * @param int $id
     * @param array $data
     * @return Inspection|null
     */
    public function updateInspection(int $id, array $data): ?Inspection
    {
        return $this->inspectionRepository->update($id, $data);
    }

    /**
     * Delete an inspection
     *
     * @param int $id
     * @return bool
     */
    public function deleteInspection(int $id): bool
    {
        return $this->inspectionRepository->delete($id);
    }

    /**
     * Get inspections due for renewal
     *
     * @param int $daysThreshold
     * @return Collection
     */
    public function getInspectionsDueForRenewal(int $daysThreshold = 30): Collection
    {
        return $this->inspectionRepository->getDueForRenewal($daysThreshold);
    }
}