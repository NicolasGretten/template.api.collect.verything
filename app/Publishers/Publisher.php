<?php

namespace App\Publishers;

use App\Traits\IdTrait;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Publisher {
    use IdTrait;

    /**
     * @throws Exception
     */
    public static function publish($class) {
        $message = json_decode(json_encode($class->message));

        DB::connection('pubsub')
            ->insert('insert into '.$message->to.' (sns_id, message, topic, created_at) values (?, ?, ?, ?)', [substr('sns_' . md5(Str::uuid()),0 ,25) ,base64_encode(json_encode($message->body)), $message->to, Carbon::now()]);
    }
}
