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
        'deviations',
        'audits',
        'comment'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'deviations',
        'audits',
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
