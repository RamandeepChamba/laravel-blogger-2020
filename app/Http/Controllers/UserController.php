<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Blog;
use App\User;
use App\Traits\DeleteBlog;
use App\Traits\DeleteComment;
use App\Traits\FilterBlogs;

class UserController extends Controller
{
    use DeleteBlog;
    use DeleteComment;
    use FilterBlogs;

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
