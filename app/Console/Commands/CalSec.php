<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CalSecInd;
use App\Jobs\CalSecBud;
use App\Jobs\ClearSecTable;

class CalSec extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cal:sec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cal Industry and Building SEC';

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
        dispatch(new ClearSecTable());
        dispatch(new CalSecInd());
        dispatch(new CalSecBud());
    }
}
