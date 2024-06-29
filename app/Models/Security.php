<?php

namespace App\Models;

/**
 * Security model representing the securities kpi table.
 */
class Security extends BaseModel
{

    protected $fillable = [
        'result',
        'comment'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'result',
        'comment',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the line that owns the security.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * Get the shift that owns the security.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
