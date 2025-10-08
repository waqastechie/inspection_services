<?php

namespace App\Repositories;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonnelRepository
{
    /**
     * Get all personnel with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Personnel::orderBy('first_name')->paginate($perPage);
    }

    /**
     * Find personnel by ID
     *
     * @param int $id
     * @return Personnel|null
     */
    public function findById(int $id): ?Personnel
    {
        return Personnel::find($id);
    }

    /**
     * Create a new personnel
     *
     * @param array $data
     * @return Personnel
     */
    public function create(array $data): Personnel
    {
        return Personnel::create($data);
    }

    /**
     * Update a personnel
     *
     * @param int $id
     * @param array $data
     * @return Personnel|null
     */
    public function update(int $id, array $data): ?Personnel
    {
        $personnel = $this->findById($id);
        
        if (!$personnel) {
            return null;
        }
        
        $personnel->update($data);
        return $personnel;
    }

    /**
     * Delete a personnel
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $personnel = $this->findById($id);
        
        if (!$personnel) {
            return false;
        }
        
        return $personnel->delete();
    }

    /**
     * Get active personnel
     *
     * @return Collection
     */
    public function getActive(): Collection
    {
        return Personnel::where('is_active', true)->orderBy('first_name')->get();
    }
}