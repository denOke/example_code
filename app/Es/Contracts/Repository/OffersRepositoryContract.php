<?php

namespace App\Es\Contracts\Repository;

interface OffersRepositoryContract
{
    public function getItems(array $filter = [], string $sort = 'start_date', string $order = 'desc');
}
