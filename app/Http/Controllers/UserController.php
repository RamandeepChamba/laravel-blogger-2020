<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Blog;
use App\User;
use App\Traits\DeleteBlog;
use App\Traits\DeleteComment;

class UserController extends Controller
{
    use DeleteBlog;
    use DeleteComment;

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
        Storage::deleteDirectory('uploads/' . $user->id);
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

        return json_encode($response);
    }

    public function blogs($user_id)
    {
        $blogs = Blog::where('user_id', '=', $user_id)->get();

        return view('blog.index', $data = compact('blogs'));
    }
}
