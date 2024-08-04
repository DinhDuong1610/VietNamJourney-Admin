<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    use HasFactory;

    protected $table = 'chat_user';

    protected $fillable = [
        'user_from', 'user_to', 'content', 'image',
    ];

    /**
     * Get the sender user.
     */
    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_from', 'id');
    }

    /**
     * Get the recipient user.
     */
    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_to', 'id');
    }
}
