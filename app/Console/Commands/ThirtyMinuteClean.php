<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Email;
use Carbon\Carbon;

class ThirtyMinuteClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailcare:thirty {--before_date= : threshold of email date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean emails soft deleted (emails and attachments in the database and files)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getThresholdDate(): Carbon
    {
        $date = $this->option( 'before_date', null );
        if (empty( $date )) {
            return Carbon::now()->subMinutes( 30 );
        }
        return new Carbon( $date );
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line( "-------------------------------------------------" );
        $this->line( "AutoClean command executed at " . Carbon::now() );
        $date = $this->getThresholdDate();

        $this->line( "Cleaning Threshold date " . $date );

        $emails = Email::where( 'created_at', '<', $date )->delete();
        $this->info( "Emails to clean: " . $emails );

        return 0;
    }
}
