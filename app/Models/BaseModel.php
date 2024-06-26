<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * BaseModel provides common attributes for all models.
 */
class BaseModel extends Model
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
