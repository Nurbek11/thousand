<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\PostRubric;
use App\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function searchInRubric(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);
        if (sizeof(Rubric::all()->where('title', '=', $request->title)) == 0) {
            return response(['mesage' => 'There is no such rubric']);
        }
        $rubric = Rubric::all()->where('title', '=', $request->title)->last();
        $postRubrics = PostRubric::all()->where('rubric_id', '=', $rubric->id);

        $posts = [];
        foreach ($postRubrics as $postRubric) {
            array_push($posts, Post::all()->where('id', '=', $postRubric->post_id));
        }

        if (sizeof($posts) == 0) {
            return response(['message' => 'There is no posts of this rubric']);
        } else {
            return response(['posts' => $posts]);
        }


    }

    public function showById(Request $request)
    {
        $request->validate([
            'post_id' => 'required'
        ]);
        $post = Post::find($request->post_id);
        if ($post == null) {
            return response('no post with such id');
        } else {
            return response(['post' => $post]);
        }
    }

    public function showByTitle(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);
        $post = Post::all()->where('title', '=', $request->title);
        if (sizeof($post) == 0) {
            return response('no post with such title');
        } else {
            return response(['posts' => $post]);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'announcement'=>'required',
            'body' => 'required'
        ]);
        if (Post::all()->where('title', '=', $request->title)->last() == null) {
            $post = new Post();
            $post->author_id = Auth::id();
            $post->title = $request->title;
            $post->announcement = $request->announcement;
            $post->body = $request->body;
            $post->save();
            if ($request->rubrics != null) {
                for ($i = 0; $i < sizeof($request->rubrics); $i++) {
                    $postRubricId = Rubric::all()->where('title', '=', $request->rubrics[$i])->last();
                    if ($postRubricId != null) {
                        $postRubrics = new PostRubric();
                        $postRubrics->post_id = $post->id;
                        $postRubrics->rubric_id = $postRubricId->id;
                        $postRubrics->save();
                    }
                }
            }
            return response(['post' => $post]);
        } else {
            return response(['message' => 'duplicate posts']);
        }

    }

    public function searchByRubric(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        if (sizeof(Post::all()->where('title', '=', $request->title)) == 0) {
            return response('no post found');
        }
        $post = Post::all()->where('title', '=', $request->title)->last();
        $postRubrics = PostRubric::all()->where('post_id', '=', $post->id);
        if (sizeof($postRubrics) == 0) {
            return response('the post is not registered in any rubric');
        }
        for ($i = 0; $i < sizeof($postRubrics); $i++) {
            $rubrics[$i] = Rubric::all()->where('id', '=', $postRubrics[$i]->rubric_id);
        }


        return response(['post' => $post, 'rubrics' => $rubrics]);
    }
}
