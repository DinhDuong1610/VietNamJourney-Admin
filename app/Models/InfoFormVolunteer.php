<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoFormVolunteer extends Model
{
    use HasFactory;

    protected $table = 'info_form_volunteer';

    protected $fillable = [
        'fullname',
        'birth',
        'phone',
        'email',
        'address',
    ];

    public function formVolunteer()
    {
        return $this->hasOne(FormVolunteer::class, 'infoId', 'id');
    }
}
