<?php

namespace App\Xero;

use App\Models\TrackingCategory;
use App\Models\TrackingOption;

class StoreTrackingCategories
{

    public function store($id, $trackingCategories)
    {
        foreach ($trackingCategories as $trackingCategory)
        {
            $category = TrackingCategory::updateOrCreate(
                [
                    'project_api_system_id' => $id,
                    'tracking_category_id' => $trackingCategory['tracking_category_id']
                ],
                [
                    'project_api_system_id' => $id,
                    'tracking_category_id' => $trackingCategory['tracking_category_id'],
                    'name' => $trackingCategory['name'],
                    'status' => $trackingCategory['status']
                ]
            );

            $this->storeOptions($category->id, $trackingCategory['options']);
        }
    }

    public function storeOptions($trackingCategoryID, $options)
    {
        foreach ($options as $option)
        {
            TrackingOption::updateOrCreate(
                [
                    'tracking_category_id' => $trackingCategoryID,
                    'tracking_option_id' => $option['tracking_option_id']
                ],
                [
                    'tracking_category_id' => $trackingCategoryID,
                    'tracking_option_id' => $option['tracking_option_id'],
                    'name' => $option['name'],
                    'status' => $option['status']
                ]
            );
        }
    }

}

