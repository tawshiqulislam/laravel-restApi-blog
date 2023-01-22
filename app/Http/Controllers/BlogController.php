<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body'  => 'required',
            'image' => [
                'sometimes',
                'mims:png,jpg,jpeg',
                'nullable'
            ]
        ]);
    }
}
