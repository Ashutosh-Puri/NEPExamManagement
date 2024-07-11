<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class allcls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        Artisan::call('event:clear');
        Artisan::call('config:clear');
        Artisan::call('auth:clear-resets');
        Artisan::call('view:clear');
        Artisan::call('queue:clear');

        $logFiles = glob(storage_path('logs/*.log'));
        foreach ($logFiles as $file) {
            if (is_file($file)) {
                file_put_contents($file, '');
            }
        }


        $this->info('Daily clearing done successfully !');
    }
}
