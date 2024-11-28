<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Traits\HttpResponses;
use App\Models\Post;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class PostController extends Controller
{
    use HttpResponses;
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        if (!$posts) return $this->errorResponse('', 'The posts not found', 404);
        return $this->successResponse($posts);
    }
    public function show(string $slug)
    {

        $post = Post::where('slug', $slug)->first();

        if (!$post) return $this->errorResponse('', 'The post not found', 404);
        return $this->successResponse($post);
    }

    public function store(StorePostRequest $request)
    {
        $request->validated($request->all());

        Post::create([
            'title' => $request->get('title'),
            'short_description' => $request->get('short_description'),
            'description' => $request->get('description'),
            'slug' => $request->get('slug'),
            'user_id' => Auth::user()->id,
            'category_ids' => $request->get('category_ids'),
        ]);


        if ($request->get('thumbnail')) {
            $post = Post::where('slug', $request->get('slug'))
                ->where('user_id', Auth::user()->id)
                ->first();
            $post = (new FileService)->updateImage($post, $request);
            $post->save();
        }

        return $this->successResponse('', 'Created post successfully', 201);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        if (!$post) return $this->errorResponse('', 'The post not found', 404);

        $request->validated($request->all());

        $post->update([
            'title' => $request->get('title'),
            'short_description' => $request->get('short_description'),
            'description' => $request->get('description'),
            'slug' => $request->get('slug'),
            'user_id' => Auth::user()->id,
            'category_ids' => $request->get('category_ids'),
        ]);


        if ($request->get('thumbnail')) {
            $post = Post::where('slug', $request->get('slug'))
                ->where('user_id', Auth::user()->id)
                ->first();
            $post = (new FileService)->updateImage($post, $request);
            $post->save();
        }

        return $this->successResponse('', 'Updated post successfully', 200);
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) return $this->errorResponse('', 'The post not found', 404);

        if ($post->thumbnail) File::delete(public_path() . '/files/' . $post->thumbnail);

        $post->delete();

        return $this->successResponse('', 'Deleted post successfully', 200);
    }
}