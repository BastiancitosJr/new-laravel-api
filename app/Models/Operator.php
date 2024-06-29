<?php

namespace App\Models;

/**
 * Operator model representing the operators table.
 */
class Operator extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'rut',
        'line_id',
        'area_id',
        'user_id'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'rut',
        'line',
        'area',
        'user'
    ];

    /**
     * Get the line that owns the operator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * Get the area that owns the operator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the user that owns the operator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
