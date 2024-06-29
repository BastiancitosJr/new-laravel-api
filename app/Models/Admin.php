<?php

namespace App\Models;

/**
 * Admin model representing the admins table.
 */
class Admin extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id'
    ];

    /**
     * @var array
     */
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
