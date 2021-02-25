<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Blog;
use App\User;
use App\Traits\DeleteBlog;
use App\Traits\DeleteComment;
use App\Traits\FilterBlogs;
use App\Traits\Connections\I4I;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    use DeleteBlog;
    use DeleteComment;
    use FilterBlogs;
    use I4I;

    public function __construct()
    {
        $this->middleware('auth')->except(['blogs']);
        $this->middleware('ajax')->only(['destroy']);
    }

    public function destroy()
    {
        $user = User::findOrFail(auth()->user()->id); 
        // Delete user likes
        $user->likes()->delete();
        // Delete uploaded files (profile pics etc.)
        if (App::environment('production')) {  
            $client = $this->getI4IConnection();
            // Delete avatar        
            if(isset($user->profile->avatar)) {
                // https://cdn.image4.io/ramandeepchamba/fe9fc509-1121-4177-b567-e4e4b0391950.jpeg
                $avatarName = substr($user->profile->avatar, strrpos($user->profile->avatar, '/'));
                $response = $client->deleteImage($avatarName);
            }
        }
        elseif (App::environment('local')) {
            Storage::deleteDirectory('uploads/' . $user->id);
        }
        // Delete profile
        $user->profile()->delete();
        // Delete blogs with its comments and likes
        // from other users as well
        foreach ($user->blogs as $blog) {
            $this->deleteBlog($blog->id, true);
        }
        // Delete user comments
        foreach ($user->comments as $comment) {
            $this->deleteComment($comment->id, true);
        }
        // Delete user
        $response = (object)NULL;
        $response->user = $user;
        $user->delete();

        session()->flash('message', 'Account deleted successfully!');
        session()->flash('flash-class', 'success');

        return json_encode($response);
    }

    public function blogs($user_id)
    {
        $blogs = Blog::where('user_id', '=', $user_id)->get();

        $data = $this->filterBlogs($blogs);
        $blogs = $data['blogs'];
        $filters = $data['filters'];
        $blogs = $blogs->paginate(2);
        return view('blog.index', $data = compact(['blogs', 'filters']));
    }
}
