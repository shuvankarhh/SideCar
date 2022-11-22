<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrackingCategory;

class TrackingOption extends Model
{
    use HasFactory;
    protected $guarded = [];

    const ACTIVE = 'ACTIVE';
    const ARCHIVED = 'ARCHIVED';

    public function trackingCategory(){
        return $this->hasOne(TrackingCategory::class, 'id', 'tracking_category_id');
    }
}
