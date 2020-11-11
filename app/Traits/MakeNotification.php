<?php

namespace App\Traits;

use Illuminate\Notifications\Messages\BroadcastMessage;

trait MakeNotification {
    
    public function toDatabase($notifiable)
    {
        $data = $this->prepareData($notifiable);
        return $data;
    }

    public function toBroadcast($notifiable)
    {
        $data = $this->prepareData($notifiable);
        return new BroadcastMessage([
            'data' => $data
        ]);
    }

    public function broadcastType()
    {
        $type = get_class($this);
        $cutFrom = strripos($type, '\\') + 1;
        return substr($type, $cutFrom);
    }

}
?>