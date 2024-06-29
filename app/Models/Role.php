<?php

namespace App\Models;

/**
 * Role model representing the roles table.
 */
class Role extends BaseModel
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];
    /**
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'description',
        'users'
    ];

    /**
     * Get the users associated with the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
