<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $attrs = $request->only(app(Blog::class)->getFillable());

        $attrs['creator_id'] = auth()->id();

        $blog = Blog::create($attrs);

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filePath = $image->storeAs('public/blogs', Str::random(10).'.'.$extension);
            $storagePath = Storage::url($filePath);

            $blog->iamge()->create([
                'path' => $storagePath
            ]);
        }

        return redirect(route('dashboard'))
            ->with('message', 'Blog Created Successfully');
    }

    public function show(Blog $blog){
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog){
        return view('blogs.edit',compact('blog'));
    }

    public function update(Request $request,Blog $blog){
        $request->validate([
            'title' => 'required',
            'body'  => 'required',
            'image' => [
                'sometimes',
                'mims:png,jpg,jpeg',
                'nullable'
            ]
        ]);

        $attrs = $request->only(app(Blog::class)->getFillable());

        $blog ->update($attrs);

        if($request->hasFile('image')) {
            if($blog->image && File::exists($blog->image->file_path)) {
                File::delete($blog->image->file_path);
            }
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filePath = $image->storeAs('public/blogs', Str::random(10).'.'.$extension);
            $storagePath = Storage::url($filePath);

            $blog->iamge()->create([
                'path' => $storagePath
            ]);
        }

        return redirect(route('dashboard'))
            ->with('message', 'Blog Updated Successfully');
    }

    public function delete(Blog $blog) {
        if($blog->image && File::exists($blog->image->file_path)){
            File::delete($blog->image->file_path);
        }

        $blog->delete();

        return redirect(route('dashboard'))
            ->with('message', 'Blog Deleted Successfully');
    }

}
