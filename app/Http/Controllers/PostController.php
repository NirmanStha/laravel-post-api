<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use ErrorException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{


            // $posts = Post::join("users", "posts.user_id", "=", "users.id")
            // ->select("posts.title", "users.name", "posts.description", "posts.image", "posts.created_at", "posts.updated_at", "posts.id")->orderBy("created_at", "asc")
            // ->get();

            // if ($posts->count() > 0) {
            //     return response()->json([
            //         "message" => "request successful",
            //         "posts" => $posts
            //     ]);
            // }

            // return response()->json([
            //     "message" => "no post available",
            // ]);
            return  PostResource::collection(Post::with('user')->get());
            }
            catch(Exception $e) {
                return response()->json([
                    "message" => $e->getMessage()
                ],500);
            }
    }

    /**
     * Get a specific post.
     */
    public function getMyPost()
    {

        try {
            $user = Auth::user();
            $mypost = Post::where("user_id", $user->id)->get();
            return PostResource::collection($mypost);
        } catch (Exception $e) {
            Log::error('Error fetching specific post: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => "string|required|max:255|min:1",
            "description" => "string|required|min:1|max:300",
            "image" => "nullable|mimes:jpg,png,jpeg"
        ]);

        $post = new Post();

        try {
            $user = Auth::user();
            $imgName = null;

            // Handling file upload
            if ($request->hasFile("image")) {
                $img = $request->file("image");
                $extension = $img->getClientOriginalExtension();
                $imgName = $request->user_id . time() . "." . $extension;
                $img->move(public_path("uploads/post_image/"), $imgName);
                $path = asset("uploads/post_image/" . $imgName);
            }

            // Store post data
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = $user->id;
            $post->image = $imgName;
            $post->save();

            return new PostResource($post);
        } catch (Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            return new PostResource($post);
        } catch (Exception $e) {
            Log::error('Error fetching post: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize("update", $post);

        $request->validate([
            'title' => 'string|sometimes|max:255|min:1',
            'description' => 'string|sometimes|min:1|max:300',
            'image' => 'sometimes|mimes:jpg,png,jpeg'
        ]);

        try {
            $imgName = $post->image;
            if ($request->hasFile('image')) {
                if ($imgName) {
                    $oldImg = public_path('uploads/post_image/' . $imgName);
                    if (File::exists($oldImg)) {
                        File::delete($oldImg);
                    }
                }

                $img = $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imgName = $post->user_id . time() . '.' . $ext;
                $img->move(public_path('uploads/post_image/'), $imgName);

                $post->image = $imgName;
            }

            if ($request->has('title')) {
                $post->title = $request->input('title');
            }
            if ($request->has('description')) {
                $post->description = $request->input('description');
            }



            $post->save();



            return new PostResource($post);
        } catch (Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            if ($post) {
                $post->delete();
                return response()->json([
                    "message" => "post deleted successfully"
                ]);
            }
        } catch (ErrorException $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
