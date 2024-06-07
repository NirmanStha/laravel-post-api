<?php

namespace App\Http\Resources;

use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "image" => $this->image !== null ? asset("uploads/post_image/". $this->image):"",
            "user" => new UserResource($this->whenLoaded("user")),
            "created_at" =>$this->created_at,
            "updated_at" => $this->updated_at

        ];
    }
}
