<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'Address_ID';

}
