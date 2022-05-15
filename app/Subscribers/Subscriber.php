<?php

namespace App\Subscribers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class Subscriber
{
    public $payload;

    public $sns_id;

    public $topic;

    public function __construct($payload)
    {
        $payload = json_decode($payload['payload']);

        $this->payload = json_decode(base64_decode($payload->message));
        $this->sns_id = $payload->sns_id;
        $this->topic = $payload->topic;
        $this->read();
    }

    public function read()
    {
        try {
            DB::connection('pubsub')->table($this->topic)
                ->where('sns_id', '=', $this->sns_id)
                ->update(['read_at' => Carbon::now()]);
            DB::connection('pubsub')->table(env('SUBSCRIBER_SQS_QUEUE'))
                ->where('sns_id', '=', $this->sns_id)
                ->update(['read_at' => Carbon::now()]);
        } catch(PDOException | Exception  $e) {
            report($e);
        }
    }

    public function failed($error)
    {
        try {
            DB::connection('pubsub')->table($this->topic)
                ->where('sns_id', '=', $this->sns_id)
                ->update(['failed_at' => Carbon::now(), 'error_message' => $error->getMessage()]);
            DB::connection('pubsub')->table(env('SUBSCRIBER_SQS_QUEUE'))
                ->where('sns_id', '=', $this->sns_id)
                ->update(['failed_at' => Carbon::now(), 'error_message' => $error->getMessage()]);
        } catch(PDOException | Exception  $e) {
            report($e);
        }
    }

    /**
     * @throws Exception
     */
    public function checkJsonResponse($response)
    {
        if (substr($response->getStatusCode(), 0, 1) != 2){
            throw new Exception($response);
        }
    }
}
