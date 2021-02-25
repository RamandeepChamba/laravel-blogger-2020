<?php

namespace App\Traits\Connections;
use \Image4IO\Image4IOApi;

trait I4I {

    public function getI4IConnection()
    {
        $apiKey = env('I4I_API_KEY');
        $apiSecret = env('I4I_API_SECRET');
        $client = new Image4IOApi($apiKey, $apiSecret);
        return $client;
    }
}
?>