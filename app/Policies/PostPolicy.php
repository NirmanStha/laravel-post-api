<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class PostPolicy
{


    public function update(User $user, Post $post): bool
    {
        Log::info("this is post policy => post id" . $post->id . " user_id=>" . $user->id);
        return $user->id === $post->user_id;
    }

    public function viewSpecific(User $user, Post $post) : bool {
         return $user->id ===$post->user_id;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }


}
