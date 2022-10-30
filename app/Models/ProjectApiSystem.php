<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiAccessToken;

class ProjectApiSystem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTO('App\Models\Project', 'Project_ID', 'project_id');
    }

    public function apiAccessToken(){
        return $this->hasOne(ApiAccessToken::class, 'project_api_system_id', 'id' );
    }
}
