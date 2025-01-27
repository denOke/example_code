<?php

namespace App\Es\Contracts\Service;

interface BranchServiceContract
{
    public function getIdByCode(string $code);
}
