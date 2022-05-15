<?php
namespace App\Publishers;

use Illuminate\Support\Carbon;

class ExamplePublisher extends Publisher
{
    public array $message;

    public function __construct($suspension)
    {
        $this->message = [
            'to' => 'subscription_status',
            'body' => [
                'topic' => 'subscription-suspension-end',
                'ids' => [
                    'subscription_id' => $suspension->subscription_id
                ],
                'data' => [
                    'duration' => $suspension->duration,
                    'start_at' => Carbon::createFromFormat('Y-m-d H:i:s', $suspension->start_at)->getTimestamp(),
                    'end_at'   => Carbon::createFromFormat('Y-m-d H:i:s', $suspension->start_at)->addMonths($suspension->duration)->getTimestamp()
                ]
            ]
        ];
    }
}
