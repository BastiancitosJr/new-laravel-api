<?php

namespace App\Models;

/**
 * Line model representing the lines table.
 */
class Line extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'area_id',
        'user_id'
    ];

    protected $visible = [
        'id',
        'name',
        'area',
        'user',
        'operators'
    ];

    /**
     * Get the area that owns the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the user that owns the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the operators for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operators()
    {
        return $this->hasMany(Operator::class);
    }
}
