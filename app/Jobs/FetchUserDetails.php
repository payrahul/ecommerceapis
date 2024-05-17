<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class FetchUserDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $newInfo;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @return void
     */
    public function __construct($userId, $newInfo)
    {
        $this->userId = $userId;
        $this->newInfo = $newInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->userId);
        if ($user) {
            $user->update($this->newInfo);
        }
    }
}
