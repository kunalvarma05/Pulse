<?php

namespace Pulse\Models;

use Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    'password', 'remember_token',
    ];

    /**
     * Hash any password being inserted by default.
     *
     * @param string $password
     *
     * @return Pulse\Models\User
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
        return $this;
    }

    /**
     * The Accounts owned by the User
     *
     * @return Pulse\Models\Account
     */
    public function accounts()
    {
        return $this->hasMany('Pulse\Models\Account');
    }

    /**
     * The Activities of the User
     *
     * @return Pulse\Models\Activity
     */
    public function activities()
    {
        return $this->hasMany('Pulse\Models\Activities');
    }

    /**
     * The ScheduledTransfers owned by the User
     *
     * @return Pulse\Models\ScheduledTransfer
     */
    public function scheduledTransfers()
    {
        return $this->hasMany('Pulse\Models\ScheduledTransfer');
    }


}
