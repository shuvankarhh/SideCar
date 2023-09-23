<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Client extends Model
{
    use HasFactory;

    protected $primaryKey = 'Client_ID';

    // company->Company_Name
    public function company(){
        return $this->hasOne( Company::class, 'Company_ID', 'Company_ID');
    }

    public function userClient(){
        return $this->hasMany(UserClient::class, 'Client_ID');
    }

}
