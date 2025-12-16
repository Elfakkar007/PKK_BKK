<?php

namespace App\Console\Commands;

use App\Models\JobVacancy;
use Illuminate\Console\Command;

class AutoCloseVacancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vacancies:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto close expired or full job vacancies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vacancies = JobVacancy::where('status', '!=', 'closed')
            ->where('status', '!=', 'rejected')
            ->get();

        $closed = 0;

        foreach ($vacancies as $vacancy) {
            if ($vacancy->shouldBeClosed()) {
                $vacancy->markAsClosed();
                $closed++;
            }
        }

        $this->info("Successfully closed {$closed} vacancies.");

        return Command::SUCCESS;
    }
}
