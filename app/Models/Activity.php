<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * Table
     */
    protected $table = "activities";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['transaction_id', 'transaction_type', 'user_id', 'status'];

    /**
     * The User the Activity belongs to
     *
     * @return Pulse\Models\User
     */
    public function user()
    {
        return $this->belongsTo('Pulse\Models\User', 'user_id');
    }

    /**
     * The Transaction(Action|Transfer), the activity is for
     *
     * @return Pulse\Models\Transfer|Pulse\Models\Action
     */
    public function transaction()
    {
        return $this->morphTo();
    }
}
