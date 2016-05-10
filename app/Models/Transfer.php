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
    protected $fillable = ['type', 'file_id', 'from_account_id', 'to_account_id', 'scheduled_at'];

    /**
     * The File being transferred
     *
     * @return Pulse\Models\File
     */
    public function file()
    {
        return $this->belongsTo('Pulse\Models\File', 'file_id');
    }

    /**
     * The Account, the file is being transferred from
     *
     * @return Pulse\Models\Account
     */
    public function fromAccount()
    {
        return $this->belongsTo('Pulse\Models\Account', 'from_account_id');
    }

    /**
     * The Account, the file is being transferred to
     *
     * @return Pulse\Models\Account
     */
    public function toAccount()
    {
        return $this->belongsTo('Pulse\Models\Account', 'to_account_id');
    }
}
