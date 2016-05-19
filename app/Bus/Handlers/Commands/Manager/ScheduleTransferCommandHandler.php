<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Models\File;
use Pulse\Models\Transfer;
use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\ScheduleTransferCommand;

class ScheduleTransferCommandHandler
{

    /**
     * Handles ScheduleTransferCommand
     * @param  ScheduleTransferCommand $command
     * @return Pulse\Models\Transfer
     */
    public function handle(ScheduleTransferCommand $command)
    {

        //Key
        $key = $command->file;
        //File
        $file = $command->account->files()->firstOrCreate(['key' => $key]);

        //Scheduled At
        $scheduled_at = $command->scheduled_at;

        //Schedule Transfer
        $transfer = $file->transfers()->create([
            'type' => 'copy',
            'from_account_id' => $command->account->id,
            'to_account_id' => $command->newAccount->id,
            'location'      => $command->location,
            'scheduled_at'  => $scheduled_at,
        ]);

        return $transfer;

    }
}
