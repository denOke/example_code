<?php

namespace App\Http\Controllers\Api\Offers;

use App\Es\Contracts\Service\OffersServiceContract;
use App\Es\Exceptions\Branch\NotFoundBranchByCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Offers\OffersListRequest;
use Illuminate\Http\JsonResponse;

class OffersController extends Controller
{
    private array $filterField = [
        'branch_code',
    ];

    public function list(OffersListRequest $request): JsonResponse
    {
        $filter = $request->only($this->filterField);

        try {
            $offers = resolve(OffersServiceContract::class)->getActualOffers($filter);
        } catch (NotFoundBranchByCode $exception) {
            return response()->json([
                'error' => [
                    'message' => 'Branch not found by the provided code.',
                    'code' => 'NOT_FOUND_BRANCH',
                ],
            ], 404);
        }

        return response()->json([
            'offers' => $offers,
        ]);
    }
}
