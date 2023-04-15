<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearTmpFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:clear-tmp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function handle(): int
    {
        info('This Message From Schedule');
        $tmpDirectories = Storage::disk('local')->directories('public/tmp');
        foreach($tmpDirectories as $tmpDirectory){
            $tmpTimeStampedDirectory = explode('/' , $tmpDirectory);
            $tmpTimeStampedDirectory = $tmpTimeStampedDirectory[count($tmpTimeStampedDirectory) - 1];

            $currentDate = date('Y_m_d_H');
            if($currentDate > $tmpTimeStampedDirectory){
                Storage::disk('local')->deleteDirectory($tmpDirectory);
            }
        }
        return CommandAlias::SUCCESS;
    }
}
