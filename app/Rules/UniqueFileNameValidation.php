<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\InvoiceImport;

class UniqueFileNameValidation implements Rule
{
    public function __construct()
    {   }

    public function passes($attribute, $value)
    {
        if (!($value instanceof UploadedFile) || !$value->isValid()) {
            return false;
        }
        
        return InvoiceImport::where('filename', $value->getClientOriginalName())->count() <= 0;
    }

    public function message()
    {
        return "Filename already exists in database :attribute";
    }
}