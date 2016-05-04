<?php
namespace Pulse\Bus\Handlers\Commands\Action;

use Pulse\Models\Activity;
use Pulse\Bus\Commands\Action\RecordActionCommand;

class RecordActionCommandHandler {

    /**
     * Handle RecordActionCommand
     * @param  RecordActionCommand $command
     * @return Pulse\Models\Activity
     */
    public function handle(RecordActionCommand $command)
    {
        //Create File Record
        $file = $command->account->files()->firstOrCreate(['key' => $command->key, 'account_id' => $command->account->id]);
        //Action
        $action = $file->actions()->create(['type' => 'rename', 'account_id' => $command->account->id]);
        //Activity
        $activity = $action->activities()->create(['user_id' => $command->user->id, 'status' => Activity::COMPLETED]);

        return $activity;
    }

}