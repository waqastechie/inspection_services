<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    /**
     * Get all clients
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Client::orderBy('client_name')->get();
    }

    /**
     * Get active clients
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return Client::where('is_active', true)->orderBy('client_name')->get();
    }

    /**
     * Find client by ID
     *
     * @param int $id
     * @return Client|null
     */
    public function findById($id)
    {
        return Client::find($id);
    }

    /**
     * Create a new client
     *
     * @param array $data
     * @return Client
     */
    public function create(array $data)
    {
        return Client::create($data);
    }

    /**
     * Update client
     *
     * @param int $id
     * @param array $data
     * @return Client
     */
    public function update($id, array $data)
    {
        $client = $this->findById($id);
        if ($client) {
            $client->update($data);
        }
        return $client;
    }

    /**
     * Delete client
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $client = $this->findById($id);
        if ($client) {
            return $client->delete();
        }
        return false;
    }
}