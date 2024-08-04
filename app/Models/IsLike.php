<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsLike extends Model
{
    use HasFactory;

    protected $table = 'islike';

    protected $fillable = [
        'User_ID', 'Post_ID',
    ];

    // IsLike's user
    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'id');
    }

    // IsLike's post
    public function post()
    {
        return $this->belongsTo(Post::class, 'Post_ID', 'Post_ID');
    }
}
