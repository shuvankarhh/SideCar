<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrackingOption;

class TrackingCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function trackingOptions(){
        return $this->hasMany(TrackingOption::class, 'tracking_category_id', 'id' );
    }

}
