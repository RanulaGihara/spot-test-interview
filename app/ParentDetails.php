<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentDetails extends Model
{
    protected $table = 'parents';
    protected $fillable = [
        'name', 'email', 'contact_number', 'nic', 'address', 'contact_person',
    ];

}
