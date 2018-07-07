<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ClearSecTable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

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
		DB::delete("truncate table sec_cals");
        DB::delete("truncate table sec_averages");
        DB::delete("delete from sec_products");
        DB::update("ALTER TABLE sec_products AUTO_INCREMENT = 1");
        DB::delete("delete from main_groups");
		DB::update("ALTER TABLE main_groups AUTO_INCREMENT = 1");
    }
}
