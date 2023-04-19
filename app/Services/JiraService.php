<?php

namespace App\Services;

use App\Http\Livewire\Output;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Cache\Repository;

class JiraService
{
    protected string $ajs_anonymous_id;
    protected string $atlassian_xsrf_token;
    protected string $cloud_session_token;
    protected string $jira_api_url;

    public function __construct()
    {
        $this->ajs_anonymous_id = config('services.jira_service.ajs_anonymous_id');
        $this->atlassian_xsrf_token = config('services.jira_service.atlassian_xsrf_token');
        $this->cloud_session_token = config('services.jira_service.cloud_session_token');
        $this->jira_api_url = config('services.jira_service.jira_api_url');

    }

    public function getCookie()
    {
        return 'ajs_anonymous_id='.$this->ajs_anonymous_id.'; atlassian.xsrf.token=.'.$this->atlassian_xsrf_token.'; cloud.session.token='.$this->cloud_session_token;
    }

    public function logTask(string $taskNumber, array $messages, int $timeSpent, string $timeStarted, string $taskFormat)
    {

        $comment = $this->formatComment($messages, $taskFormat);
        // $comment = str_replace("'", "\"", $comment);
        $started = $this->formatTimeStarted($timeStarted);
        $timeSpent = Output::formatTimeBy30($timeSpent);

        try {
            $response = Http::withHeaders([
                'Cookie' => $this->getCookie(),
                'Accept' => 'application/json',
            ])->post($this->jira_api_url.$taskNumber.'/worklog',[
                    "comment" => $comment,
                    "started" => $started,
                    "timeSpent" => $timeSpent,
            ]);

            $response->throw();

            return true;
        } catch (Exception $e) {
            return false;
        }

        return null;
    }

    protected function formatTimeStarted($timeStarted) {
        $timeStarted = date('H:i', strtotime($timeStarted));
        $time_parts = explode(':', $timeStarted);
        $hour = $time_parts[0];
        $minute = $time_parts[1];

        return now()->setTime($hour, $minute, 0, 0)->setTimezone('-0400')->format('Y-m-d\TH:i:s.uO');
    }

    protected function formatComment($messages, $taskFormat) {
        $comment = "";

        if ($taskFormat == 'task') {
            foreach($messages as $message => $time) {
                $comment .= $message." - ".Output::formatTimeBy30($time);

                // Check if the current iteration is not the last one
                if ($message !== array_key_last($messages)) {
                    $comment .= "\n";
                }
            }

            return $comment;
        }

        foreach($messages as $key => $m) {
            $comment .= $m['time']." - ";

            if ($m['fifteen']) {
                $comment .= "15:";
            }

            $comment .= $m['work'];

            // Check if the current iteration is not the last one
            if ($key !== array_key_last($messages)) {
                $comment .= "\n";
            }

        }

        return $comment;
    }
}
