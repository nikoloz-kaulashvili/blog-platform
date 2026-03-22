<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Services\PostService;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function index(Request $request)
    {
        $posts = $this->postService->getFiltered($request, auth()->user());

        $categories = Category::all();

        return view('posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::query()->get();

        return view('posts.create', compact('categories'));
    }

    public function show($id)
    {
        $post = Post::with(['comments.user', 'comments.replies.user'])->findOrFail($id);

        return view('posts.show', compact('post'));
    }

    public function store(StorePostRequest $request)
    {
        $this->postService->create($request->validated());

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $categories = Category::query()->get();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($post, $request->validated());

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return redirect()->route('posts.index');
    }

    public function approve(Post $post)
    {
        $this->postService->approve($post);

        return redirect()->back();
    }

    public function reject(Post $post)
    {
        $this->postService->reject($post);

        return redirect()->back();
    }
}
