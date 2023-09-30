<?php

namespace App\Models;

use App\Models\Client;
use App\Models\UserProject;
use App\Models\ProjectApiSystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'Project_ID';

    public function client(){
        return $this->hasOne(Client::class, 'Client_ID', 'Client_ID');
    }

    public function userProject()
    {
        return $this->hasMany(UserProject::class, 'Project_ID');
    }

    public function projectApiSystem(){
        return $this->hasOne(ProjectApiSystem::class, 'project_id', 'Project_ID');
    }
}
