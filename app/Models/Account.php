<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * Table
     */
    protected $table = "accounts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'uid', 'name', 'access_token', 'picture', 'provider_id', 'user_id',
    ];

    /**
     * The User the account belongs to
     *
     * @return Pulse\Models\User
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     * The Provider the account belongs to
     *
     * @return Pulse\Models\Provider
     */
    public function provider()
    {
        return $this->belongsTo('Provider', 'provider_id');
    }

    /**
     * The Files owned by the Account
     *
     * @return Pulse\Models\File
     */
    public function files()
    {
        return $this->hasMany('File');
    }

    /**
     * The Transfer, in which the Account is the account,
     * where a file is being transferred from
     *
     * @return Pulse\Models\Transfer
     */
    public function fromTransfer()
    {
        return $this->hasMany('Transfer', 'from_account_id');
    }

    /**
     * The Transfer, in which the Account is the account,
     * where a file is being transferred to
     *
     * @return Pulse\Models\Transfer
     */
    public function toTransfer()
    {
        return $this->hasMany('Transfer', 'to_account_id');
    }

}
