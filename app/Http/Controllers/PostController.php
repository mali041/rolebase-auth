<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use function Pest\Laravel\json;
use function PHPUnit\Framework\returnArgument;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'images', 'categories']);

        if (auth()->user()->role === 'admin') {
            $posts = $query->paginate(3);
        }else {
            $posts = $query->get();
        }
        $categories = Category::all();

        return view('posts.index', compact('posts', 'categories'));
    }

    public function loadPostsData(Request $request)
    {
        $categoryId = $request->input('category');
        $query = Post::with(['user', 'images', 'categories']);

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $posts = $query->get();

        $html = view('posts.posts-table', compact('posts'))->render();
        return response()->json(['html' => $html]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('posts.create', compact($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageController $imageController)
    {
        $request->validate([
            'title' => 'required|string|unique:posts,title',
            'description' => 'required|string|max:300',
            'user_id' => 'required|integer',
            'categories.*' => 'required|exists:categories,id',
        ]);

        $post = new Post;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = $request->user_id;
        $post->save();

        // Delegate image upload to the ImageController
        if ($request->hasFile('images')) {
            $imageController->upload($request, $post);
        }

        if ($request->has('categories')) {
            $post->categories()->attach($request->categories);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post = Post::with(['user', 'images', 'categories'])->find($post->id);
//        return $post;
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $data = $post->images;

        return view('posts.update', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, ImageController $imageController)
    {
        $request->validate([
            'title' => 'required|string|unique:posts,title,' . $post->id,
            'description' => 'required|string|max:300',
            'user_id' => 'required|integer|exists:users,id',
            'categories.*' => 'required|exists:categories,id',
        ]);

        // Update post details
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
        ]);

        // Delegate image upload to the ImageController
        if ($request->has('remove_images')) {
            $imageController->removeImage($request, $post);
        }

        // Handle new image uploads
        if ($request->hasFile('new_images')) {
            $imageController->update($request, $post);
        }

        // Sync categories
        $post->categories()->sync($request->categories ?? []);

        return response()->json(['message' => 'Post updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, ImageController $imageController)
    {
        $imageController->delete($post);

        $post->delete();

        return response()->json(['message' => 'Post and associated images deleted successfully.']);
    }
}
