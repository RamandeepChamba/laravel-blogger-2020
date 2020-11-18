<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\Comment;
use App\User;
use \Auth;
use App\Traits\DeleteBlog;
use App\Traits\FilterBlogs;
use App\Notifications\FollowingAddedBlog;
use App\Events\BlogUpdated as BlogUpdatedEvent;

class BlogController extends Controller
{
    use DeleteBlog;
    use FilterBlogs;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'getComments']);
        $this->middleware('ajax')->only(['getComments']);
    }

    public function index()
    {
        $blogs = Blog::all();
        
        $data = $this->filterBlogs($blogs);
        $blogs = $data['blogs'];
        $filters = $data['filters'];

        $blogs = $blogs->paginate(2);
        return view('blog.index', $data = compact(['blogs', 'filters']));
    }

    public function create()
    {
        $blog = new Blog;
        return view('blog.create', compact('blog'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData();
        $data['user_id'] = Auth::id();

        $blog = Blog::create($data);

        // Notify all the followers
        $leader = Auth::user();
        foreach ($leader->followers as $follower) {
            $follower->notify(new FollowingAddedBlog($leader, $blog));
        }

        session()->flash('message', 'Blog added successfully!');
        session()->flash('flash-class', 'success');

        return redirect('/blogs/' . $blog->id);
    }

    public function show($blog_id)
    {
        $blog = Blog::findOrFail($blog_id);

        // Check if blog liked by auth user
        if (Auth::user() && $blog->likes()->count()) {
            if ($blog->likes()->where('user_id', '=', Auth::user()->id)->first()) {
                $liked = [1];
            }
            else {
                $liked = [];
            }
        }
        else {
            $liked = [];
        }

        $commentNode = [];
        if(request('highlightedComment')) {
            $commentNode = array_merge($commentNode,
                $this->commentParentNode(request('highlightedComment')));
        }
        $highlightCommentNode = json_encode($commentNode);

        $liked = json_encode($liked);
        return view('blog.show', compact(['blog', 'liked', 'highlightCommentNode']));
    }

    public function edit($blog_id)
    {
        $blog = Blog::findOrFail($blog_id);
        // Check if auth user has right
        if ($blog->user_id !== Auth::id()) {
            abort(401, "Not your blog");
        }
        return view('blog.edit', compact('blog'));
    }
    
    public function update($blog_id)
    {
        $data = $this->validatedData();

        $blog = Blog::findOrFail($blog_id);

        foreach ($data as $k => $v) {
            $blog->$k = $v;
        }

        $blog->save();
        session()->flash('message', 'Blog updated successfully!');
        session()->flash('flash-class', 'success');
        // Broadcast updation of blog
        BlogUpdatedEvent::dispatch($blog_id);
        return redirect('/blogs/' . $blog_id);
    }

    public function destroy($id)
    {
        $this->deleteBlog($id);
        session()->flash('message', 'Blog deleted successfully!');
        session()->flash('flash-class', 'success');
        return redirect('/blogs');
    }

    public function getComments($id)
    {
        $blog = Blog::findOrFail($id);
        
        $comments = $blog->comments()
        ->with([
            'user:id,name',
            'user.profile', 
            'likes' => function ($query) {
                // Don't know how to alias (likes AS userLiked)
                if(Auth::user()) {
                    $query->where('user_id', '=', request()->user()->id);
                }
                else {
                    $query->where('user_id', '=', -1);
                }
            }
        ])
        ->withCount('replies')
        ->withCount('likes')
        ->get()->toArray();

        return $comments;
    }

    protected function commentParentNode($id)
    {
        $comment = Comment::find($id);
        $commentNode = [];
        if ($comment) {
            $commentNode[] = $comment->id;
            if($comment->parent_id) {
                $commentNode = array_merge($commentNode, 
                    $this->commentParentNode($comment->parent_id));
            }
        }
        return $commentNode;
    }

    protected function validatedData()
    {
        return request()->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:5'
        ]);
    }
}
