<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;


trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable)) {
            try {
                $value = base64_decode($value);
            } catch (\Exception $e) {
                return $value;
            }
        }
        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = base64_encode($value);
        }

        return parent::setAttribute($key, $value);
    }
}
