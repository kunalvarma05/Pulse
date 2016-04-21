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
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    'access_token',
    ];

    /**
     * The User the account belongs to
     *
     * @return Pulse\Models\User
     */
    public function user()
    {
        return $this->belongsTo('Pulse\Models\User', 'user_id');
    }

    /**
     * The Provider the account belongs to
     *
     * @return Pulse\Models\Provider
     */
    public function provider()
    {
        return $this->belongsTo('Pulse\Models\Provider', 'provider_id');
    }

    /**
     * The Files owned by the Account
     *
     * @return Pulse\Models\File
     */
    public function files()
    {
        return $this->hasMany('Pulse\Models\File');
    }

    /**
     * The Transfer, in which the Account is the account,
     * where a file is being transferred from
     *
     * @return Pulse\Models\Transfer
     */
    public function fromTransfer()
    {
        return $this->hasMany('Pulse\Models\Transfer', 'from_account_id');
    }

    /**
     * The Transfer, in which the Account is the account,
     * where a file is being transferred to
     *
     * @return Pulse\Models\Transfer
     */
    public function toTransfer()
    {
        return $this->hasMany('Pulse\Models\Transfer', 'to_account_id');
    }

    /**
     * The Actions performed in this Account
     *
     * @return Pulse\Models\Action
     */
    public function actions()
    {
        return $this->hasMany('Pulse\Models\Action');
    }
}
