<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Post;
use App\Traits\HttpResponses;

// use MongoDB\BSON\ObjectId;

class CategoryController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        if (!$category) return $this->errorResponse('', 'No category', 404);
        return $this->successResponse($category, 'Success');
    }

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) return $this->errorResponse('', 'No category', 404);
        return $this->successResponse($category, 'Success');
    }

    public function getPostByCategory($slug) {
        $category = Category::where('slug', $slug)->first();
        if (!$category) return $this->errorResponse('', 'No category', 404);
        $posts = Post::where('category_ids',  $category->id)->get();
        return $this->successResponse($posts, 'Success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $status = 1;
        $request->validated($request->all());

        if ($request->get('status')) $status = $request->get('status');

        Category::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'status' => $status
        ]);

        return $this->successResponse('', 'Add new category successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if (!$category) return $this->errorResponse('', 'Category not found', 404);

        $request->validated($request->all());

        $category->update([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'status' => $request->get('status') ?? 1
        ]);

        return $this->successResponse('', 'Update category successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) return $this->errorResponse('', 'Category not found', 404);

        $category->delete();

        return $this->successResponse('', 'Delete category successfully', 200);
    }
}