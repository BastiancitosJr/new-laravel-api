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

    /**
     * @var array
     */
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
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
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
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function peerObservations()
    {
        return $this->hasMany(PeerObservations::class);
    }

    /**
     * Get the security for the line.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
    public function mounthlyProgrammingProgress()
    {
        return $this->hasOne(MonthlyProgrammingProgress::class);
    }
}
