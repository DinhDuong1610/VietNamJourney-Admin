<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;
    protected $table = 'volunteer';

    protected $fillable = [
        'userId', 'campaignId', 'status', 'formId'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaignId', 'id');
    }

    public function formVolunteer()
    {
        return $this->belongsTo(FormVolunteer::class, 'formId', 'id');
    }
}
