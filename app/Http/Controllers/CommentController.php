<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        try{

            $comment = Comment::where("post_id", $post->id)->get();

        if($comment !== null)
            return response()->json([
            "message" => "query successfull",
            "comments" => $comment,
            "post_id" => $post->id
        ],200);
        else{
            return response()->json([
                        "message" => "query successfull",
                    "comments" => ""
                ],200);
            }
        }
        catch(Exception $e) {
                return response()->json([
                    "message" => $e->getMessage()
                ],500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Post $post,Request $request)
    {
        try{

            $user = Auth::user();
            $comment = new Comment();
            $request->validate([
                "comment_text" => "required|string|max:255|min:1"
            ]);

            $comment->user_id = $user->id;
            $comment->post_id = $post->id;
            $comment->comment_text = $request->comment_text;

            $comment->save();

            return response()->json([
                "message" => "comment created successfully",
            ],201);

        }
        catch(Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }

    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, Request $request, Comment $comment)
    {
        try{
            Gate::authorize("update", [ $comment, $post,]);
            $request->validate([
                "comment_text" => "required | string |max:255 | min:1"
            ]);

            $comment->comment_text = $request->comment_text;
            $comment->save();
            return response()->json([
                "message" => "comment updated successfully"
            ]);

        }
        catch(Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
       try{
        Gate::authorize("destroy", [$comment, $post]);
        $comment->delete();
        return response()->json([
            "message" => "comment successfully deleted"
        ],200);
       }
       catch(Exception $e) {
        return response()->json([
            "message" => $e->getMessage()
        ],500);
       }
    }
}
