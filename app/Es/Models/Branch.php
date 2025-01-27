<?php

namespace App\Es\Models;

use App\Es\Traits\FillsUuid;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use FillsUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'code',
        'timezone',
        'external_id',
    ];
}
