<?php

namespace App\Es\Models;

use App\Es\Traits\FillsUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    use FillsUuid;
    public $incrementing = false;
    protected $table = 'partners_offers';

    protected $fillable = [
        'name',
        'main_text',
        'active',
        'additional_text',
        'second_additional_text',
        'start_date',
        'end_date',
        'active',
        'link',
        'user_type',
        'channel',
        'sections',
        'subcategory',
        'sort',
        'target',
    ];

    protected $casts = [
        'payload' => 'array',
        'subcategory' => 'array',
        'city' => 'array',
        'branch_code' => 'array',
    ];

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(
            Branch::class,
            'partners_offers_intermediate_branch',
            'id',
            'branch_id'
        );
    }
}
