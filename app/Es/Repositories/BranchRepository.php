<?php

namespace App\Es\Repositories;

use App\Es\Contracts\Repository\BranchRepositoryContract;

class BranchRepository extends Repository implements BranchRepositoryContract
{
    public function getByCode(string $code)
    {
        return $this->getModel()->where('code', $code)->get();
    }
}
