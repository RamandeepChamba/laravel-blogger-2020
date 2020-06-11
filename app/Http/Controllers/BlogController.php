<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use \Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $blogs = Blog::all();

        return view('blog.index', $data = compact('blogs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:5',
            'content' => 'required'
        ]);
        $data['user_id'] = Auth::id();

        Blog::create($data);

        return redirect('/blog');
    }
}
