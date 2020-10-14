<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Traits\FetchProfile;

class FollowerController extends Controller
{
    use FetchProfile;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Follow
    public function follow(Request $request)
    {
        return $this->followOrUnfollow($request, 'follow');
    }

    // Unfollow
    public function unfollow(Request $request)
    {
        return $this->followOrUnfollow($request, 'unfollow');
    }

    // Show followers
    public function showFollowers($leader_id)
    {
        $followers = User::findOrFail($leader_id)->followers;

        if($followers->count()) {
            $followers = $followers
            ->toQuery()
            ->select('id','name')
            ->with('profile:user_id,avatar')
            ->get();
        }
        
        return view('follower.index', compact(['followers']));
    }

    // Show followings
    public function showFollowings($follower_id)
    {
        $followings = User::findOrFail($follower_id)->followings;
        
        if($followings->count()) {
            $followings = $followings
            ->toQuery()
            ->select('id','name')
            ->with('profile:user_id,avatar')
            ->get();
        }
        
        return view('follower.index', compact(['followings']));
    }

    // Check if current user follows given leader
    public function isFollowing($leader_id)
    {
        $follower = User::findOrFail(request()->user()->id);
        $response = (object)NULL;
        $response->isFollowing = $follower->followings->contains($leader_id);
        return json_encode($response);
    }

    // Helpers

    protected function followOrUnfollow(Request $request, $action)
    {
        if (($action !== 'follow') && ($action !== 'unfollow')) {
            return;
        }

        // Validate request
        $data = $request->validate([
            'leader_id' => 'required',
        ]);
        $leader_id = $data['leader_id'];
        $follower = User::findOrFail($request->user()->id);
        // User can't follow/unfollow themselves
        if ($leader_id === $follower->id) {
            abort(response("Cannot $action yourself", 400));
        }
        // Check if leader exists
        $leader = User::findOrFail($leader_id);
        
        if($action == 'follow') {
            $follower->followings()->attach($leader);
        }
        else {
            $follower->followings()->detach($leader);
        }

        $response = (object)NULL;
        $response->profile = $this->fetchProfile($leader_id);
        return json_encode($response);
    }
}
