<?php

namespace App\Es\Services;

use App\Es\Contracts\Repository\OffersRepositoryContract;
use App\Es\Contracts\Service\BranchServiceContract;
use App\Es\Contracts\Service\OffersServiceContract;
use App\Es\DTO\Offers\OfferDTO;
use App\Es\Models\Offer;

class OffersService implements OffersServiceContract
{
    public function getActualOffers($filter = [], $sort = 'created_at', $order = 'desc')
    {
        $filter['actual'] = true;

        if ($filter['branch_code']) {
            $filter['branch_id'] = resolve(BranchServiceContract::class)->getIdByCode($filter['branch_code']);
        }

        return resolve(OffersRepositoryContract::class)->getItems($filter, $sort, $order)->map(function (Offer $offer) {
            return OfferDTO::createByModel($offer);
        });
    }
}
