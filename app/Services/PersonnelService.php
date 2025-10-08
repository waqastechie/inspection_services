<?php

namespace App\Services;

use App\Models\Personnel;
use App\Repositories\PersonnelRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonnelService
{
    protected $personnelRepository;

    /**
     * PersonnelService constructor
     *
     * @param PersonnelRepository $personnelRepository
     */
    public function __construct(PersonnelRepository $personnelRepository)
    {
        $this->personnelRepository = $personnelRepository;
    }

    /**
     * Get all personnel with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPersonnel(int $perPage = 15): LengthAwarePaginator
    {
        return $this->personnelRepository->getAll($perPage);
    }

    /**
     * Find personnel by ID
     *
     * @param int $id
     * @return Personnel|null
     */
    public function findById(int $id): ?Personnel
    {
        return $this->personnelRepository->findById($id);
    }

    /**
     * Create a new personnel
     *
     * @param array $data
     * @return Personnel
     */
    public function createPersonnel(array $data): Personnel
    {
        return $this->personnelRepository->create($data);
    }

    /**
     * Update a personnel
     *
     * @param int $id
     * @param array $data
     * @return Personnel|null
     */
    public function updatePersonnel(int $id, array $data): ?Personnel
    {
        return $this->personnelRepository->update($id, $data);
    }

    /**
     * Delete a personnel
     *
     * @param int $id
     * @return bool
     */
    public function deletePersonnel(int $id): bool
    {
        return $this->personnelRepository->delete($id);
    }

    /**
     * Get active personnel
     *
     * @return Collection
     */
    public function getActivePersonnel(): Collection
    {
        return $this->personnelRepository->getActive();
    }
}