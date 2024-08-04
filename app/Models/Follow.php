<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = 'follow';

    protected $fillable = [
        'Follower_ID',
        'Following_ID',
    ];

    /**
     * Get the user that follows another user.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'Follower_ID', 'id');
    }

    /**
     * Get the user that is followed by another user.
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'Following_ID', 'id');
    }
}
