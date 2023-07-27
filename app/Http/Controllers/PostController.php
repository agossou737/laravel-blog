<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //tous les postes

    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'DESC')
                ->with('user:id,name,image')
                ->withCount('comments', 'likes')
                ->with("likes", function ($like) {
                    return $like->where("user_id", auth()->user()->id)->select("id", "user_id", "post_id")->get();
                })
                ->get()
        ], 200);
    }

    //un post par id

    public function show($id)
    {
        return response([
            "post" => Post::where("id", $id)->withCount("comments", "likes")->get()
        ]);
    }

    // creer un post

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'body' => "required|string"
        ]);

        $image = $this->savedImage($request->image, "posts");

        $post = Post::create([
            "body" => $attrs["body"],
            "user_id" => auth()->user()->id,
            "image" => $image
        ]);

        return response([
            "message" => "poste créé",
            'post' => $post
        ], 200);
    }

    //mettre a jour post


    public function update(Request $request, $id)
    {
        $psot = Post::find($id);

        if (!$psot) {
            return response([
                "message" => "Le post n'a pas été trouvé"
            ], 403);
        }


        if ($psot->id != auth()->user()->id) {
            return response([
                "message" => "Access non autorisé"
            ], 403);
        }

        $attrs = $request->validate([
            'body' => "required|string"
        ]);

        $psot->update([
            "body" => $attrs["body"],

        ]);

        return response([
            "message" => "poste modifié",
            'post' => $psot
        ], 200);
    }

    public function destroy($id)
    {
        $psot = Post::find($id);

        if (!$psot) {
            return response([
                "message" => "Le post n'a pas été trouvé"
            ], 403);
        }


        if ($psot != auth()->user()->id) {
            return response([
                "message" => "Access non autorisé"
            ], 403);
        }

        $psot->comments()->delete();
        $psot->likes()->delete();
        $psot->delete();

        return response([
            "message" => "Le post a pas été supprimé"
        ], 200);
    }
}
