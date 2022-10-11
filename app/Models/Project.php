<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'Project_ID';

    public function client(){
        return $this->hasOne(Client::class, 'Client_ID', 'Client_ID');
    }
}
