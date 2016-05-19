<?php
namespace Pulse\Console\Commands;

use Mail;
use Carbon\Carbon;
use Pulse\Models\Transfer;
use Illuminate\Console\Command;
use Pulse\Bus\Commands\Manager\TransferFileCommand;

class RunScheduledTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pulse:scheduled-transfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute any pending scheduled transfers.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lower = Carbon::now()->timezone("Asia/Kolkata");
        $upper = Carbon::now()->timezone("Asia/Kolkata");

        $lower->subSeconds($lower->second + 1);
        $upper->subSeconds($upper->second - 1)->addMinute();

        $transfers = Transfer::where('scheduled_at', '>', $lower)->where('scheduled_at', '<', $upper)->get();

        foreach ($transfers as $transfer) {
            try {
                $user = $transfer->fromAccount->user;
                //Transfer File
                $transferedFile = dispatch(new TransferFileCommand(
                    $user,
                    $transfer->fromAccount,
                    $transfer->toAccount,
                    $transfer->file->key,
                    $transfer->location
                    ));

                if($transferedFile) {
                    $transferData = [
                        'toAccount' => $transfer->toAccount->name,
                        'fromAccount' => $transfer->fromAccount->name,
                        'file' => $transfer->file->key
                    ];

                    Mail::send('emails.transfer-success', ['name' => $user->name, 'transfer' => $transferData], function ($message) use ($user) {
                        $message->to($user->email, $user->name)->subject('Transfer Successful!');
                    });

                    $transfer->delete();
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        }
    }
}
