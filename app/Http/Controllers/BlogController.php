<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\Comment;
use \Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'getComments']);
        $this->middleware('ajax')->only(['getComments']);
    }

    public function index()
    {
        $blogs = Blog::all();

        return view('blog.index', $data = compact('blogs'));
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
        $liked = json_encode($liked);
        return view('blog.show', compact(['blog', 'liked']));
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
        
        return redirect('/blogs/' . $blog_id);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        // Check if auth user has right
        if ($blog->user_id !== Auth::id()) {
            abort(401, "Not your blog");
        }
        $blog->delete();
        return redirect('/blogs');
    }

    public function getComments($id)
    {
        $blog = Blog::findOrFail($id);
        
        $comments = $blog->comments()
        ->with([
            'user:id,name', 
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

    protected function validatedData()
    {
        return request()->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:5'
        ]);
    }
}
