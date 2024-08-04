<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBox extends Model
{
    use HasFactory;

    protected $table = 'chat_box';

    protected $fillable = [
        'user_1', 'user_2',
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_1', 'id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user_2', 'id');
    }
}
