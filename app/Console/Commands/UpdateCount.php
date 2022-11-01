<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\PollVote;
use Illuminate\Support\Facades\DB;
use App\Model\PollOption;

class UpdateCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatecount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $data = PollVote::query()
            ->select('poll_options', DB::raw('count(*) as count'))
            ->groupBy('poll_options')
            ->get()
            ->pluck('count', 'poll_options')
            ->toArray();

        foreach ($data as $key => $value) {
            PollOption::where('id',$key)->update(['user_vote_count'=>$value]);
        }
        echo "cron finished";
    }
}
