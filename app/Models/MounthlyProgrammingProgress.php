<?php

namespace App\Models;

/**
 * MounthlyProgrammingProgress model representing the mounthly_programming_progresses kpi table.
 */
class MounthlyProgrammingProgress extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'mounthly_order',
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'mounthly_order',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the line that owns the mounthly programming progress.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * Get the shift that owns the mounthly programming progress.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
