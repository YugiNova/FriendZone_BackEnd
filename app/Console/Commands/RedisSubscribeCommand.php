<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe redis chanel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "listening on redis";
        Redis::subscribe('notifications', function ($message) {
            echo $message;
           
        });
    }
}
