<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function store (Request $request)
    {
        Gate::authorize('create_post');
        $post = Post::create($request->all());
        return $post;
    }

    public function update (Request $request, $id)
    {
        $post = Post::findOrFail($id);
        Gate::authorize('update_post', $post);

        $post->update($request->all());
        return $post;
    }
}
