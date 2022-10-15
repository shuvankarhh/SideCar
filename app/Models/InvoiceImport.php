<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceImport extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function invoices()
    {
        return $this->hasMany(InvoiceImport::class, 'invnum','invnum');
    }

    public function vendor()
    {
        return $this->belongsTo(Campaign::class, 'vendorid');
    }
}
