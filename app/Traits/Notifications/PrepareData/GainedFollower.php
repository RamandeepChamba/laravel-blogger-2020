<?php

namespace App\Traits\Notifications\PrepareData;

trait GainedFollower {
    public function prepareData($notifiable, $follower)
    {
        $prettyFollower = (object)NULL;

        $prettyFollower->id = $follower->id;
        $prettyFollower->name = $follower->name;

        return [
            'follower' => $prettyFollower
        ];
    }
}