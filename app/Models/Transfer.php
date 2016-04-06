<?php

namespace Pulse\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /**
     * Table
     */
    protected $table = "transfers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'file_id', 'from_account_id', 'to_account_id'];

    /**
     * The File being transferred
     *
     * @return Pulse\Models\File
     */
    public function file()
    {
        return $this->belongsTo('File', 'file_id');
    }

    /**
     * The Account, the file is being transferred from
     *
     * @return Pulse\Models\Account
     */
    public function fromAccount()
    {
        return $this->belongsTo('Account', 'from_account_id');
    }

    /**
     * The Account, the file is being transferred to
     *
     * @return Pulse\Models\Account
     */
    public function toAccount()
    {
        return $this->belongsTo('Account', 'to_account_id');
    }

    /**
     * The Scheduled Transfer
     *
     * @return Pulse\Models\ScheduledTransfer
     */
    public function scheduledTransfer()
    {
        return $this->hasOne('ScheduledTransfer', 'transfer_id');
    }

}
