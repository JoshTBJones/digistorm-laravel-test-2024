<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use Carbon\Carbon;

class LogDailyContactActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:log-daily-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log the number of contacts created and updated daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();

        $createdCount = Contact::whereDate('created_at', $today)->count();
        // Count contacts updated today but exclude those that were also created today
        $updatedCount = Contact::whereDate('updated_at', $today)
            ->whereColumn('created_at', '!=', 'updated_at') // Exclude newly created records
            ->count();

        // Future implementation:
        // - Send an email to the admin with the number of contacts created and updated today
        // - Store the data in a database table for historical analysis
        // - Use soft deletes or yesterdays total to keep track of deleted contacts

        Log::info("Contacts created today: $createdCount");
        Log::info("Contacts updated today: $updatedCount");

        return Command::SUCCESS;
    }
}