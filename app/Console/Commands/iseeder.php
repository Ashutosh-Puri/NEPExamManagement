<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class iseeder extends Command
{
      /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iseeder {modelname : The name of the model}';

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
        $modelname = $this->argument('modelname'); 
        
        $students = app("App\\Models\\$modelname")->all()->toArray();

        $h=fopen('Seeder.php','w');
      
        foreach ($students as  $student) {
            fwrite($h,"\n".$modelname."::create(");
            fwrite($h,var_export($student,true));
            fwrite($h,");\n\n");
        }
        fclose($h);

        $this->info("Done");
    }
}
