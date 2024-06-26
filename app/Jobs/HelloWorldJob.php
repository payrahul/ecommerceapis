<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\ProductCategories;


class HelloWorldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        throw new Exception('Intentional Failure');
        sleep(3); // Delay for 30 seconds
        for($i= 2;$i<= 10;$i++){

            ProductCategories::create([
                'name'=>'test'.$i,
                'user_id'=>'1',
                'category_isActive'=>'1',
            ]);
            sleep(3); // Delay for 30 seconds
        }

        
    }
}
