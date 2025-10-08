<?php
namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Get all clients
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllClients()
    {
        return $this->clientRepository->getAll();
    }

    /**
     * Get active clients
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveClients()
    {
        return $this->clientRepository->getActive();
    }

    /**
     * Find client by ID
     *
     * @param int $id
     * @return Client|null
     */
    public function findById($id)
    {
        return $this->clientRepository->findById($id);
    }

    /**
     * Create a new client
     *
     * @param array $data
     * @return Client
     */
    public function createClient(array $data)
    {
        return $this->clientRepository->create($data);
    }

    /**
     * Update client
     *
     * @param int $id
     * @param array $data
     * @return Client
     */
    public function updateClient($id, array $data)
    {
        return $this->clientRepository->update($id, $data);
    }

    /**
     * Delete client
     *
     * @param int $id
     * @return bool
     */
    public function deleteClient($id)
    {
        return $this->clientRepository->delete($id);
    }
}