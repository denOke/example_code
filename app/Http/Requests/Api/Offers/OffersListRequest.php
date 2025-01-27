<?php

namespace App\Http\Requests\Api\Offers;

use App\Es\Contracts\Repository\DictionaryRepositoryContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OffersListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_code' => $this->getBranchCodeRule(),
        ];
    }

    private function getBranchCodeRule(): array
    {
        $branchList = resolve(DictionaryRepositoryContract::class)
            ->getBranchList()
            ->pluck('code')
            ->toArray();

        return ['nullable', 'string', Rule::in($branchList)];
    }
}
