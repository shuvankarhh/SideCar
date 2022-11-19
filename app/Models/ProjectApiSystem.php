<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiAccessToken;
use App\Models\ChartOfAccount;
use App\Models\TrackingCategory;

class ProjectApiSystem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTO('App\Models\Project', 'Project_ID', 'project_id');
    }

    public function chartOfAccounts(){
        return $this->hasMany(ChartOfAccount::class, 'project_api_system_id', 'id' );
    }

    public function trackingCategories(){
        return $this->hasMany(TrackingCategory::class, 'project_api_system_id', 'id' );
    }

}
