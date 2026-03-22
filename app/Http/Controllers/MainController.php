<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\PostService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request, PostService $postService)
    {
        $posts = $postService->getFiltered($request);

        $categories = Category::all();

        return view('welcome', compact('posts', 'categories'));
    }
}
