<?php

namespace App\Es\Contracts\Repository;

interface BranchRepositoryContract
{
    public function getByCode(string $code);
}
