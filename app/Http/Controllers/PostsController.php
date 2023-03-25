<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function create(){
        return view('posts.create');
    }
   

    public function store()
    {
        $data = request()->validate([
            'caption' => 'required',
            'image' => ['required','image']
        ]);

        $imgpath = request('image')->store('uploads', 'public');

        $image = Image::make(public_path("storage/{$imgpath}"))->fit(1200,1200);
        $image->save();
        
        array_push($data,"user_id");
        $data["user_id"] = auth()->id();       

        \App\Models\Post::create([
            'user_id' => $data["user_id"],
            'caption' => $data['caption'],
            'image' => $imgpath,
            ]
        );

        return redirect('/profile/' . $data['user_id']);
    }

    public function show(\App\Models\Post $post){

        return view('posts.show', compact('post'));

    }
}
