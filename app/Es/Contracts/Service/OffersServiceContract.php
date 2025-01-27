<?php

namespace App\Es\Contracts\Service;

interface OffersServiceContract
{
    public function getActualOffers($filter = [], $sort = 'created_at', $order = 'desc');
}
