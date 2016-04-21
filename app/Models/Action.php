<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * Table
     */
    protected $table = "actions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'file_id', 'account_id'];

    /**
     * The File on which the action is being performed
     *
     * @return Pulse\Models\File
     */
    public function file()
    {
        return $this->belongsTo('Pulse\Models\File', 'file_id');
    }

    /**
     * The Account, the action is being performed on
     *
     * @return Pulse\Models\Account
     */
    public function account()
    {
        return $this->belongsTo('Pulse\Models\Account', 'account_id');
    }

    /**
     * The Activities, the Action is a part of
     * @return Pulse\Models\Activity
     */
    public function activities()
    {
        return $this->morphMany('Pulse\Models\Activity', 'transaction');
    }
}
