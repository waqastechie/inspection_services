<?php

namespace App\Services;

use App\Models\Equipment;
use App\Repositories\EquipmentRepository;

class EquipmentService
{
    protected $equipmentRepository;

    public function __construct(EquipmentRepository $equipmentRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
    }

    /**
     * Get all equipment
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllEquipment()
    {
        return $this->equipmentRepository->getAll();
    }

    /**
     * Get active equipment
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveEquipment()
    {
        return $this->equipmentRepository->getActive();
    }

    /**
     * Find equipment by ID
     *
     * @param int $id
     * @return Equipment|null
     */
    public function findById($id)
    {
        return $this->equipmentRepository->findById($id);
    }

    /**
     * Create new equipment
     *
     * @param array $data
     * @return Equipment
     */
    public function createEquipment(array $data)
    {
        return $this->equipmentRepository->create($data);
    }

    /**
     * Update equipment
     *
     * @param int $id
     * @param array $data
     * @return Equipment
     */
    public function updateEquipment($id, array $data)
    {
        return $this->equipmentRepository->update($id, $data);
    }

    /**
     * Delete equipment
     *
     * @param int $id
     * @return bool
     */
    public function deleteEquipment($id)
    {
        return $this->equipmentRepository->delete($id);
    }

    /**
     * Get equipment due for calibration
     *
     * @param int $daysThreshold
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEquipmentDueForCalibration($daysThreshold = 30)
    {
        return $this->equipmentRepository->getDueForCalibration($daysThreshold);
    }
}