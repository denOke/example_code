<?php

namespace App\Providers;

use App\Es\Contracts\Repository\BranchRepositoryContract;
use App\Es\Contracts\Repository\OffersRepositoryContract;
use App\Es\Contracts\Service\BranchServiceContract;
use App\Es\Contracts\Service\OffersServiceContract;
use App\Es\Repositories\BranchRepository;
use App\Es\Repositories\OffersRepository;
use App\Es\Services\BranchService;
use App\Es\Services\OffersService;
use App\Es\Models\Branch;
use App\Es\Models\Offer;
use Illuminate\Support\ServiceProvider;

class OffersProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OffersRepositoryContract::class, function () {
            return new OffersRepository(new Offer());
        });

        $this->app->bind(BranchRepositoryContract::class, function () {
            return new BranchRepository(new Branch());
        });

        $this->app->singleton(OffersServiceContract::class, function () {
            return new OffersService();
        });

        $this->app->singleton(BranchServiceContract::class, function () {
            return new BranchService();
        });
    }
}
