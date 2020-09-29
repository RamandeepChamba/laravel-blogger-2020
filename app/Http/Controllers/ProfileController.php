<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Profile;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
        $this->middleware('ajax')->only(
            ['store']
        );
    }

    public function show($user_id)
    {
        // Fetch profile
        $profile = $this->fetchProfile($user_id);

        if(!$profile) {
            abort(404);
        }
        
        $profile = json_encode($profile);

        return view('profile.show', compact(['profile']));
    }

    public function store()
    {
        // Validate request
        $data = request()->validate([
            'bio' => 'max:200|required_without:avatar',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required_without:bio',
        ]);

        $data = (object)$data;
        $auth_id = request()->user()->id;
        if (isset($data->bio) || isset($data->avatar)) {
            $profile = $this->fetchProfile($auth_id);
            
            if(!$profile) {
                abort(404);
            }
        }
        if(isset($data->bio)) {
            // Edit bio
            $profile->bio = $data->bio;
            $profile->save();
        }
        elseif(isset($data->avatar)) {
            // Upload avatar
            // Check if directory exists
            $profileDir = 'uploads/' . $auth_id . '/profile';
            if(!Storage::exists($profileDir)) {
                // Not? Create
                Storage::makeDirectory($profileDir);
            }
            // Delete previous avatar if any
            array_map('unlink', glob(storage_path('app/'. $profileDir . '/avatar*')));
            // Store avatar
            $avatarPath = Storage::putFileAs(
                $profileDir,
                $data->avatar,
                'avatar.' . $data->avatar->extension()
            );
            // Add link to profile
            $profile->avatar = asset($avatarPath);
            $profile->save();
        }

        $profile->refresh();

        $response = (object)NULL;
        $response->profile = $profile;
        return json_encode($response);
    }

    protected function fetchProfile($user_id)
    {
        $profile = Profile::where('user_id', '=', $user_id)
            ->with('user:id,name,email')
            ->first();
        return $profile;
    }
}
