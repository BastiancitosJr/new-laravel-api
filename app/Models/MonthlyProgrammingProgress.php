<?php

namespace App\Models;

/**
 * MounthlyProgrammingProgress model representing the mounthly_programming_progresses kpi table.
 */
class MonthlyProgrammingProgress extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'monthly_order',
        'line_id',
        'shift_id'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'monthly_order',
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
