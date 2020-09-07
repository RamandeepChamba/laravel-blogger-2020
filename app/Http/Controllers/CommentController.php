<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\Comment;
use App\Events\CommentDeleted;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['getReplies']);
        /*
        $this->middleware('ajax')->only(
            ['store', 'update', 'getReplies', 'destroy']
        );
        */
    }

    public function store(Request $request)
    {
        $data = $this->validatedData();
       
        $blog = Blog::findOrFail($data['blog_id']);

        if (isset($data['parent_id'])) {
            $parentComment = Comment::findOrFail($data['parent_id']);
        }

        $comment = new Comment(['comment' => $data['comment']]);
        $comment->user()->associate($request->user());

        if(isset($parentComment)) {
            $comment['parent_id'] = $parentComment->id;
        }

        $blog->comments()->save($comment);
        return Comment::where('id', '=', $comment->id)
            ->with('user:id,name')
            ->withCount('replies')
            ->first()->toJson();
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        // Check if auth user has right
        if ($comment->user_id !== auth()->user()->id) {
            abort(401, "Not your comment");
        }
        $this->deleteWithReplies($id);
        return $id;
    }


    private function deleteWithReplies($id)
    {
        $comment = Comment::findOrFail($id);

        foreach ($comment->replies as $reply) {
            $this->deleteWithReplies($reply->id);
        }

        $comment->delete();
        return;
    }

    public function update(Request $request, $id)
    {
        $data = $this->validatedData();
        $comment = Comment::where('id', '=', $id)
            ->with('user:id,name')
            ->withCount('replies')
            ->first();
        $comment->comment = $data['comment'];
        $comment->save();
        return $comment->toJson();
    }

    public function getReplies($parent_id)
    {
        $parent = Comment::findOrFail($parent_id);
        $replies = $parent->replies()
            ->with('user:id,name')
            ->withCount('replies')
            ->get()->toJson();
        return $replies;
    }

    protected function validatedData()
    {
        return request()->validate([
            'comment' => 'required|min:5',
            'blog_id' => 'nullable',
            'parent_id' => 'nullable'
        ]);
    }
}
