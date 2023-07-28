<?php

namespace App\Console\Commands;

use App\Services\JiraService;
use Illuminate\Console\Command;

class checkJiraStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jira:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $jiraService = new JiraService();
        $title = $jiraService->getTitle('DEV-1496');
        if (!empty($title)) {
            $this->info('Jira is working');
        } else {
            $this->error('Jira is not working');
        }
    }
}
