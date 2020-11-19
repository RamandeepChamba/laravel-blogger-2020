<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;
use App\Blog;
use App\Comment;
use App\User;
use App\Notifications\BlogLiked;
use App\Notifications\CommentLiked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\Notifications\PrepareData\BlogLiked as PDForBlogLiked;
use App\Traits\Notifications\PrepareData\CommentLiked as PDForCommentLiked;

class LikeController extends Controller
{
    use PDForBlogLiked, PDForCommentLiked {
        PDForBlogLiked::prepareData insteadOf PDForCommentLiked;
        PDForBlogLiked::prepareData as pDForBlogLiked;
        PDForCommentLiked::prepareData as pDForCommentLiked;
    }
    
    public function __construct()
    {
        $this->middleware(['auth'])->except(['getLikes']);
        $this->middleware(['ajax']);
    }

    public function store(Request $request)
    {
        $data = (object)$this->validatedData();
        // Can't use variable as classname because of ''
        if($data->type === 'blog') {
            $likeable = Blog::findOrFail($data->id);
            // Send notification to blog's author
            $liker = User::find(Auth::id());
            $author = $likeable->user;

            if($author->id !== $liker->id) {
                // Prevent notification duplication
                $notificationData = $this->pDForBlogLiked(null, $likeable, $liker);

                $notification = DB::table('notifications')
                    ->where('data', json_encode($notificationData))->first();
                if(!$notification) {
                    $author->notify(new BlogLiked($likeable, $liker));
                }
            }   
        }
        else if ($data->type === 'comment') {
            $likeable = Comment::findOrFail($data->id);
            // Send notification to comment's author
            $author = $likeable->user;
            $liker = User::find(Auth::id());
            
            if($author->id !== $liker->id) {
                // Prevent notification duplication
                $notificationData = $this->pDForCommentLiked(null, $likeable, $liker);

                $notification = DB::table('notifications')
                    ->where('data', json_encode($notificationData))->first();
                if(!$notification) {
                    $author->notify(new CommentLiked($likeable, $liker));
                }
            }
        }
        $like = new Like;
        $like->user()->associate($request->user());
        $savedLike = $likeable->likes()->save($like);
        $likeable->refresh();
        $response = (object)NULL;
        $response->like = $savedLike;
        $response->likesCount = $likeable->likes()->count(); 
        return json_encode($response);
    }

    public function destroy(Request $request)
    {
        $data = (object)$this->validatedData();
        // Can't use variable as classname because of ''
        if($data->type === 'blog') {
            $likeable = Blog::findOrFail($data->id);
        }
        else if ($data->type === 'comment') {
            $likeable = Comment::findOrFail($data->id);
        }

        // Check if like is there
        $like = $likeable->likes()->where('user_id', '=', $request->user()->id)->first();
        if (!$like) {
            abort(404);
        }

        // Delete like
        $like->delete();
        $response = (object)NULL;
        $response->like = $like;
        $response->likesCount = $likeable->likes()->count(); 
        return json_encode($response);
    }

    protected function validatedData($request = null)
    {
        $types = ['blog', 'comment'];
        return ($request ?? request())->validate([
            'type' => 'required|in:' . implode(',', $types),
            'id' => 'required',
        ]);
    }
}
