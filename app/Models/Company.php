<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'Company_ID';

    //$c->addresses or $c->addresses()
    public function addresses(){
        return $this->belongsToMany('App\Models\Address','company_addresses', 'Company_ID', 'Address_ID');
    }

    public function client()
    {
        return $this->belongsTO('App\Models\Client', 'Company_ID', 'Company_ID');
    }

}
