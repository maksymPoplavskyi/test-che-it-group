<?php

namespace App\Jobs;

use App\Models\Logging;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LoggingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $method;
    private $action;
    private $ip;
    private $type;
    private $data;

    /**
     * Create a new job instance.
     *
     * @param $method
     * @param $action
     * @param $ip
     * @param $type
     * @param $data
     */
    public function __construct($action, $method, $ip, $type, $data)
    {
        $this->action = $action;
        $this->method = $method;
        $this->ip = $ip;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type == "POST" || $this->type == "GET") {

            $accessKey = config('config.key');

            $ch = curl_init("http://api.ipstack.com/$this->ip?access_key=$accessKey");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($ch);
            curl_close($ch);
            $api_result = json_decode($json, true);

            Logging::insert([
                'action' => $this->action,
                'method' => $this->method,
                'ip' => $this->ip,
                'city' => $api_result['city'] ? $api_result['city'] : null,
                'country' => $api_result['country_code'] ? $api_result['country_code'] : null,
                'type' => $this->type,
                'data' => $this->data
            ]);
        }
    }
}
