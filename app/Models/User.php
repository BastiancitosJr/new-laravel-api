<?php

namespace App\Models;

use App\Enums\UserRolesEnum;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User model representing the users table.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'version',
        'role_id',
    ];

    /**
     * @var array
     */
    protected $visible = [
        'id',
        'username',
        'password',
        'created_at',
        'updated_at',
        'entity'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role->name,
            'role_id' => $this->role_id,
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the admin record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the administrative record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function administrative()
    {
        return $this->hasOne(Administrative::class);
    }

    /**
     * Get the shift manager record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shiftManager()
    {
        return $this->hasOne(ShiftManager::class);
    }

    /**
     * Get the line record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function line()
    {
        return $this->hasOne(Line::class);
    }

    /**
     * Get the operator record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function operator()
    {
        return $this->hasOne(Operator::class);
    }

    /**
     * Get the related entity (Admin, Administrative, ShiftManager, Operator, Line) based on the user's role.
     *
     * @return mixed
     */
    public function entity()
    {
        switch ($this->role_id) {
            case UserRolesEnum::ADMIN->value:
                return $this->admin();
            case UserRolesEnum::ADMINISTRATIVE->value:
                return $this->administrative();
            case UserRolesEnum::SHIFTMANAGER->value:
                return $this->shiftManager();
            case UserRolesEnum::OPERATOR->value:
                return $this->operator();
            case UserRolesEnum::LINE->value:
                return $this->line();
            default:
                return null;
        }
    }
}
