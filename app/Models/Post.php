<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    protected $primaryKey = 'Post_ID'; // Thay đổi tên cột khóa chính
    public $incrementing = true; // Nếu sử dụng auto-increment
    protected $keyType = 'int'; // Nếu khóa chính là số nguyên

    protected $fillable = [
        'User_ID', 'Content', 'Image', 'campaign_id',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'id');
    }

    public function like()
    {
        return $this->hasMany(IsLike::class, 'Post_ID', 'Post_ID');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'Post_ID', 'Post_ID');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

}
