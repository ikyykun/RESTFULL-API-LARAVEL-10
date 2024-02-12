<?php

namespace App\Http\Controllers\Api;
//import Model "Post"
use App\Models\Post;
use Illuminate\Http\Request;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
//import Resource "PostResource"
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $posts = Post::latest()->paginate(5);
        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $posts);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image' =>
            'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:10',
            'content' => 'required|min:10',
        ]);
        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());
        //create post
        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);
        //return response
        return new PostResource(
            true,
            'Data Post Berhasil Ditambahkan!',
            $post
        );
    }
    
    public function show($id)
    {
        $post = Post::find($id);
        return new PostResource(true, 'Detail Data Pastisi', $post);
    }
}
