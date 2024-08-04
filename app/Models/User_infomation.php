<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_infomation extends Model
{
    use HasFactory;
    protected $table = 'user_information';

    public function user()
    {
        return $this->belongsTo(User::class, 'UserLogin_ID', 'id');
    }
}





// public function campaigns()
// {
//     return $this->hasMany(Campaign::class, 'userId', 'User_ID');
// }
