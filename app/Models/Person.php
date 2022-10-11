<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $primaryKey = 'Person_ID';

    public function addresses(){
        return $this->belongsToMany('App\Models\Address', 'person_addresses', 'Person_ID', 'Person_ID');
    }
}
