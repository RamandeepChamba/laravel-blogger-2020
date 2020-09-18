<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;
use App\Blog;
use App\Comment;

class LikeController extends Controller
{
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
        }
        else if ($data->type === 'comment') {
            $likeable = Comment::findOrFail($data->id);
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