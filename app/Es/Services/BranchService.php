<?php

namespace App\Es\Services;

use App\Es\Contracts\Repository\BranchRepositoryContract;
use App\Es\Contracts\Service\BranchServiceContract;
use App\Es\Exceptions\Branch\NotFoundBranchByCode;

class BranchService implements BranchServiceContract
{
    /**
     * @param string $code
     * @return string
     * @throws NotFoundBranchByCode
     */
    public function getIdByCode(string $code): string
    {
        $branch = resolve(BranchRepositoryContract::class)->getByCode($code);

        if (!isset($branch['id'])) {
            throw new NotFoundBranchByCode();
        }

        return $branch['id'];
    }
}
