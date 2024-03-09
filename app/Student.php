<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'address_one',
        'city',
        'district',
        'parents_id',
    ];
    public function parent()
    {
        return $this->belongsTo(ParentDetails::class, 'parents_id', 'id');
    }
}
