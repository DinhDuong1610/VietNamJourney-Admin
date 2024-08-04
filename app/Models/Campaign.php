<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaign';

    protected $fillable = [
        'userid', 'name', 'province', 'district', 'location', 
        'dateStart', 'dateEnd', 'totalMoney', 'moneyByVNJN', 
        'timeline', 'infoContact', 'infoOrganization', 
        'image', 'description', 'plan' ,'status'
    ];

    protected $casts = [
        'timeline' => 'array',
        'infoContact' => 'array',
        'infoOrganization' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function chatGroupMessage()
    {
        return $this->hasMany(ChatGroupMessage::class, 'campaign_id', 'id');
    }

    public function volunteer()
    {
        return $this->hasMany(Volunteer::class, 'campaignId', 'id');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'campaign_id', 'id');
    }

}
