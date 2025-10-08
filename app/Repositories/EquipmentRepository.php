<?php

namespace App\Repositories;

use App\Models\Equipment;
use Carbon\Carbon;

class EquipmentRepository
{
    /**
     * Get all equipment
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll($perPage = 20)
    {
        return Equipment::with(['parentEquipment', 'items'])
            ->orderBy('equipment_category', 'asc')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get active equipment
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return Equipment::where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Find equipment by ID
     *
     * @param int $id
     * @return Equipment|null
     */
    public function findById($id)
    {
        return Equipment::find($id);
    }

    /**
     * Create new equipment
     *
     * @param array $data
     * @return Equipment
     */
    public function create(array $data)
    {
        return Equipment::create($data);
    }

    /**
     * Update equipment
     *
     * @param int $id
     * @param array $data
     * @return Equipment
     */
    public function update($id, array $data)
    {
        $equipment = $this->findById($id);
        if ($equipment) {
            $equipment->update($data);
        }
        return $equipment;
    }

    /**
     * Delete equipment
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $equipment = $this->findById($id);
        if ($equipment) {
            return $equipment->delete();
        }
        return false;
    }

    /**
     * Get equipment due for calibration
     *
     * @param int $daysThreshold
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueForCalibration($daysThreshold = 30)
    {
        $thresholdDate = Carbon::now()->addDays($daysThreshold);
        return Equipment::whereNotNull('calibration_due')
            ->where('calibration_due', '<=', $thresholdDate)
            ->where('is_active', true)
            ->orderBy('calibration_due')
            ->get();
    }
}