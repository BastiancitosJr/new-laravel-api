<?php

namespace App\Models;

/**
 * ShiftManager model representing the shift_managers table.
 */
class ShiftManager extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'area_id'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'user',
        'area'
    ];

    /**
     * Get the user that owns the shift manager record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the area that the shift manager is responsible for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the shift manager's shift.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
