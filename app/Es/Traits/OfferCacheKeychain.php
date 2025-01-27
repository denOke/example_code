<?php

namespace App\Es\Traits;

trait OfferCacheKeychain
{
    public function getPaginateKey(int $page, array $filter, $sort, $order): string
    {
        return 'OfferPaginate:' . serialize($filter) . ":$sort:$order:$page";
    }

    public function getPaginateTag(): string
    {
        return 'OfferPaginateTag';
    }

    public function getByIdKey($id): string
    {
        return 'Offer:' . $id;
    }
}
