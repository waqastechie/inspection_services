<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ClientRepository;
use App\Repositories\EquipmentRepository;
use App\Repositories\InspectionRepository;
use App\Repositories\PersonnelRepository;
use App\Services\ClientService;
use App\Services\EquipmentService;
use App\Services\InspectionService;
use App\Services\PersonnelService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register repositories
        $this->app->bind(ClientRepository::class, ClientRepository::class);
        $this->app->bind(EquipmentRepository::class, EquipmentRepository::class);
        $this->app->bind(InspectionRepository::class, InspectionRepository::class);
        $this->app->bind(PersonnelRepository::class, PersonnelRepository::class);
        
        // Register services
        $this->app->bind(ClientService::class, function ($app) {
            return new ClientService($app->make(ClientRepository::class));
        });
        
        $this->app->bind(EquipmentService::class, function ($app) {
            return new EquipmentService($app->make(EquipmentRepository::class));
        });
        
        $this->app->bind(InspectionService::class, function ($app) {
            return new InspectionService($app->make(InspectionRepository::class));
        });
        
        $this->app->bind(PersonnelService::class, function ($app) {
            return new PersonnelService($app->make(PersonnelRepository::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}