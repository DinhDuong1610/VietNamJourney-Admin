<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormVolunteer extends Model
{
    use HasFactory;

    protected $table = 'form_volunteer';

    protected $fillable = [
        'infoId',
        'reason',
    ];

    /**
     * Get the info form volunteer that owns the form volunteer.
     */
    public function infoFormVolunteer()
    {
        return $this->belongsTo(InfoFormVolunteer::class, 'infoId', 'id');
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class, 'formId', 'id');
    }
}
