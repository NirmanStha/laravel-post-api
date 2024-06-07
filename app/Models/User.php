<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Node\Expr\FuncCall;

class User extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        "name",
        "username",
        "email"

    ];
    protected $hidden = [
        'password',


    ];

    public function post() :HasMany {
        return $this->hasMany(Post::class);
    }

    public function comment(): HasMany {
        return $this->hasMany(Comment::class);
    }
}
