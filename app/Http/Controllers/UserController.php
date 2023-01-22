<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home(Request $request){
        $blogsQry = Blog::query();
        $blogsQry->when($request->keyword,fn($query) => 
        $query->where('title', 'like', '%'.$request->keyword.'%')
            ->orWhere('body', 'like', '%'.$request->keyword)
    );
        $blogs = $blogsQry->latest()->paginate(5);

        return view('dashboard', compact('blogs'));
    }

    public function show(User $user)
    {
        $blogs = Blog::where('creator_id', $user->id)->paginate(6);
        $user->blogs = $blogs;

        return view('users.profile', compact('user'));
    }
}
