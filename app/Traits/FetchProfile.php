<?php

namespace App\Traits;

use App\Profile;

trait FetchProfile {
    public function fetchProfile($user_id)
    {
        $profile = Profile::where('user_id', $user_id)
            ->with([
                'user' => function($query)
                {
                    $query->select('id','name','email');
                    $query->withCount('followers');
                    $query->withCount('followings');
                }])
            ->first();
        return $profile;
    }
}
?>