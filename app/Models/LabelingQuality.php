<?php

namespace App\Models;

/**
 * LabelingQuality model representing the labeling_qualities kpi table.
 */
class LabelingQuality extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'is_done',
        'comment',
        'line_id',
        'shift_id'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'is_done',
        'comment',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the line that owns the labeling quality.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * Get the shift that owns the labeling quality.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
