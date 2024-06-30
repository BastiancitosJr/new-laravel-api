<?php

namespace App\Models;

/**
 * Productivity model representing the productivities kpi table.
 */
class Productivity extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'packed_tons',
        'tons_produced',
        'line_id',
        'shift_id'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'packed_tons',
        'tons_produced',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the line that owns the productivity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * Get the shift that owns the productivity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
