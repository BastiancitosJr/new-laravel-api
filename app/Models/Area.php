<?php

namespace App\Models;

/**
 * Area model representing the areas table.
 */
class Area extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'shiftManagers',
        'lines',
        'operators'
    ];

    /**
     * Get the shift managers for the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shiftManagers()
    {
        return $this->hasMany(ShiftManager::class);
    }

    /**
     * Get the lines for the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lines()
    {
        return $this->hasMany(Line::class);
    }

    /**
     * Get the operators for the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operators()
    {
        return $this->hasMany(Operator::class);
    }
}
