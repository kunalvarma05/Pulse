<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * Table
     */
    protected $table = "files";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'is_encrypted', 'account_id'];

    /**
     * The Account the file belongs to
     *
     * @return Pulse\Models\Account
     */
    public function account()
    {
        return $this->belongsTo('Account', 'account_id');
    }

    /**
     * The Actions performed on the file
     *
     * @return Pulse\Models\Action
     */
    public function actions()
    {
        return $this->hasMany('Action');
    }

    /**
     * The Transfers performed on the file
     *
     * @return Pulse\Models\Transfer
     */
    public function transfers()
    {
        return $this->hasMany('Transfer');
    }

}
