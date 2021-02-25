<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Profile;
use App\Traits\FetchProfile;
use \Image4IO\Image4IOApi;
use Illuminate\Support\Facades\App;

class ProfileController extends Controller
{
    use FetchProfile;

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
            $path = 'uploads/' . $auth_id . '/profile';

            if (App::environment('production')) {
                $apiKey = env('I4I_API_KEY');
                $apiSecret = env('I4I_API_SECRET');   
                // Upload avatar
                $client = new Image4IOApi($apiKey, $apiSecret);
                // Delete avatar        
                if(isset($profile->avatar)) {
                    // https://cdn.image4.io/ramandeepchamba/fe9fc509-1121-4177-b567-e4e4b0391950.jpeg
                    $avatarName = substr($profile->avatar, strrpos($profile->avatar, '/'));
                    $response = $client->deleteImage($avatarName);
                }
                // Store avatar
                $response = $client->uploadImage($data->avatar, $path, false, false);
                $avatarPath = json_decode($response['content'])->uploadedFiles[0]->url;
            }
            elseif (App::environment('local')) {
                $profileDir = 'uploads/' . $auth_id . '/profile';
                // Delete previous avatar if any
                Storage::deleteDirectory($profileDir);

                Storage::makeDirectory($profileDir);
                // Store avatar
                $avatarPath = $data->avatar->store($profileDir);
            }
            
            // Add link to profile
            $profile->avatar = asset($avatarPath);
            $profile->save();
        }

        $profile->refresh();

        $response = (object)NULL;
        $response->profile = $profile;
        return json_encode($response);
    }
}
