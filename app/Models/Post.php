<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title", "description", "image"
    ];

    public function getImagePathAttribute() {
        $image = $this->attributes['image'];
        $imgpath = $image !==null ? asset("uploads/post_image/", $image):"";
        Log::info("this is img path in attribute" . $imgpath);
        return $image !==null ? asset("uploads/post_image/". $image): "";
    }
}
