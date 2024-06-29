<?php

namespace App\Models;

/**
 * Cleanliness model representing the cleanliness kpi table.
 */
class Cleanliness extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'done',
        'comment'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'done',
        'comment',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the line that owns the cleanliness.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * Get the shift that owns the cleanliness.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
