<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait BaseModelTrait
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'created_at',
        'updated_at',
        'deleted_at',
        'version'
    ];
}
