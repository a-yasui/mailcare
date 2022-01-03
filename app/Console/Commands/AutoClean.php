<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Email;
use Carbon\Carbon;

class AutoClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailcare:clean {--before_date= : threshold of email date}';

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
            return Carbon::now()->startOfDay()->subMonth();
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

        $emails = Email::onlyTrashed()->where( 'deleted_at', '<', $date )->get();
        $this->info( "Emails to clean: " . count( $emails ) );
        $emails->each->forceDelete();

        return 0;
    }
}
