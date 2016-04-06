<?php

namespace Pulse\Models;

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
     * The Accounts owned by the User
     *
     * @return Pulse\Models\Account
     */
    public function accounts()
    {
        return $this->hasMany('Account');
    }

    /**
     * The ScheduledTransfers owned by the User
     *
     * @return Pulse\Models\ScheduledTransfer
     */
    public function scheduledTransfers()
    {
        return $this->hasMany('ScheduledTransfer');
    }


}
