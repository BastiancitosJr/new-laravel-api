<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Shift model representing the shifts table.
 */
class Shift extends Model
{
    use HasUuids, SoftDeletes;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "shift",
        "end_time",
        'shift_manager_id'
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        "id",
        "shift",
        "end_time",
        "created_at",
        "updated_at"
    ];

    /**
     * Get the Shift Manager that owns the Shift.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shiftManager()
    {
        return $this->belongsTo(ShiftManager::class);
    }

    /**
     * Get the productivity for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productivity()
    {
        return $this->hasOne(Productivity::class);
    }

    /**
     * Get the labeling quality for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function labelingQuality()
    {
        return $this->hasMany(LabelingQuality::class);
    }

    /**
     * Get the cleanliness for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function cleanliness()
    {
        return $this->hasMany(Cleanliness::class);
    }

    /**
     * Get the peer observations for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function peerObservations()
    {
        return $this->hasOne(PeerObservations::class);
    }

    /**
     * Get the security for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function security()
    {
        return $this->hasMany(Security::class);
    }

    /**
     * Get the mounthly programming progress for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function monthlyProgrammingProgress()
    {
        return $this->hasOne(MonthlyProgrammingProgress::class);
    }
}
