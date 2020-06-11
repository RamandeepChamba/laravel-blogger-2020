<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index()
    {
        $users = [
            'Raman',
            'Vishal'
        ];
        return view('hello', $data = compact('users'));
    }
}
