<?php

namespace App\Console\Commands;

use App\Actions\TaskOneAction;
use Illuminate\Console\Command;

class YearlyPeopleDistribution extends Command
{
    protected $signature = 'app:yearly-people-distribution';

    protected $description = 'Generates a 10 x 52 table where each value is drawn from a standard uniform distribution';

    public function handle()
    {
        $result = (new TaskOneAction)->execute();

        dd($result);
    }
}
