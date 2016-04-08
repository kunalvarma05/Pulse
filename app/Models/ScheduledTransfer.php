<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledTransfer extends Model
{
    /**
     * Table
     */
    protected $table = "scheduled_transfers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status', 'transfer_id', 'user_id', 'scheduled_at'];

    /**
     * The User the scheduled transfer belongs to
     *
     * @return Pulse\Models\User
     */
    public function user()
    {
        return $this->belongsTo('Pulse\Models\User', 'user_id');
    }

    /**
     * The Transfer being scheduled
     *
     * @return Pulse\Models\Transfer
     */
    public function transfer()
    {
        return $this->belongsTo('Pulse\Models\Transfer', 'transfer_id');
    }

}
