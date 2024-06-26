<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $visible = [
        'id',
        'name',
        'user'
    ];

    /**
     * Get the user that owns the administrative record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
