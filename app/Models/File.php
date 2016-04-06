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

}
