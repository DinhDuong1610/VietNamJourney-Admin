<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'link';

    protected $fillable = [
        'User_ID', 'Social', 'Link',
    ];

    /**
     * Get the user that owns the link.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'id');
    }

}
