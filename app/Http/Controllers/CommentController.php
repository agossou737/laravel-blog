<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //Tous les commentaires d'un post

    public function index($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                "message" => "Post non trouvé"
            ]);
        }

        return response([
            'comments' => $post->comments()->with("user:id,name,image")->get()
        ], 200);
    }


    //creation de commentaire

    public function store(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                "message" => "Post non trouvé"
            ]);
        }

        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            "message" => "Commentaire créé",
         //   "post" => $post->comments()->with("user:id,name,image")
        ],200);
    }

    public function update(Request $request,$id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                "message" => "Aucun commentaire trouvé"
            ],403);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response([
                "message" => "Non autorisé"
            ],403);
        }

        $attrs = $request->validate([
            'comment' => "required|string"
        ]);

        $comment->update([
            'comment' => $attrs["comment"]
        ]);

        return response([
            "message" => "Commentaire Modifié",
         //   "post" => $post->comments()->with("user:id,name,image")
        ],200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                "message" => "Aucun commentaire trouvé"
            ],403);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response([
                "message" => "Non autorisé"
            ],403);
        }

        $comment->delete();

        return response([
            "message" => "Commentaire Supprimé",
         //   "post" => $post->comments()->with("user:id,name,image")
        ],200);
    }
}
