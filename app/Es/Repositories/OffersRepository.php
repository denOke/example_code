<?php

namespace App\Es\Repositories;

use App\Es\Contracts\Repository\OffersRepositoryContract;
use App\Es\Traits\OfferCacheKeychain;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class OffersRepository extends Repository implements OffersRepositoryContract
{
    use OfferCacheKeychain;

    public function getItems(array $filter = [], string $sort = 'start_date', string $order = 'desc')
    {
        return Cache::tags($this->getPaginateTag())->remember(
            $this->getPaginateKey(0, $filter, $sort, $order),
            self::DEFAULT_CACHE_TTL,
            function () use ($filter, $sort, $order) {
                $query = $this->buildQuery($filter, $sort, $order);
                return $query->get();
            }
        );
    }

    private function buildQuery(array $filter, string $sort, string $order): Builder
    {
        $query = $this->getModel()->query();

        $query->orderBy($sort, $order);

        if (isset($filter['active'])) {
            $query->where('active', (bool)$filter['active']);
        }

        if (isset($filter['actual'])) {
            $this->applyActualFilter($query);
        }

        if (isset($filter['branch_id'])) {
            $this->applyBranchFilter($query, $filter['branch_id']);
        }

        return $query;
    }

    private function applyActualFilter(Builder $query): void
    {
        $query->where('active', true)
            ->where(function ($subQuery) {
                $subQuery->where('start_date', '<=', Carbon::now())
                    ->orWhereNull('start_date');
            })
            ->where(function ($subQuery) {
                $subQuery->where('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            });
    }

    private function applyBranchFilter(Builder $query, string $branchId): void
    {
        $query->whereHas('branches', function ($subQuery) use ($branchId) {
            $subQuery->where('branch_id', $branchId);
        });
    }
}
