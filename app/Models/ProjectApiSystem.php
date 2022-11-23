<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiAccessToken;
use App\Models\ChartOfAccount;
use App\Models\TrackingCategory;
use App\Traits\Encryptable;

class ProjectApiSystem extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [];

    protected $encryptable = [
        'api_key',
        'api_secret'
    ];

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
