<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'user';

    protected $fillable = [
        'Userame',
        'Password',
        'is_admin',
    ];


    public function is_admin()
    {
        return $this->is_admin == 1;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Password' => 'hashed',
        ];
    }

    public function userInformation()
    {
        return $this->hasOne(User_infomation::class, 'UserLogin_ID', 'id');
    }

    public function campaign()
    {
        return $this->hasMany(Campaign::class, 'userId', 'id');
    }

    public function chatBoxSend()
    {
        return $this->hasMany(ChatBox::class, 'user_1', 'id');
    }

    public function chatBoxReceive()
    {
        return $this->hasMany(ChatBox::class, 'user_2', 'id');
    }

    public function ChatGroupMessage()
    {
        return $this->hasMany(ChatGroupMessage::class, 'user_from', 'id');
    }

    public function chatUserSend()
    {
        return $this->hasMany(ChatUser::class, 'user_from', 'id');
    }

    /**
     * Get the chat messages received by this user in chat_user.
     */
    public function chatUserReceive()
    {
        return $this->hasMany(ChatUser::class, 'user_to', 'id');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'User_ID', 'id');
    }

    public function link()
    {
        return $this->hasMany(Link::class, 'User_ID', 'id');
    }

    public function likedPost()
    {
        return $this->belongsToMany(IsLike::class, 'User_ID', 'id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'Follower_ID', 'id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'Following_ID', 'id');
    }

    public function volunteer()
    {
        return $this->hasMany(Volunteer::class, 'userId', 'id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'User_ID', 'id');
    }

    public function email()
    {
        return $this->hasMany(Email::class, 'userId');
    }
}
