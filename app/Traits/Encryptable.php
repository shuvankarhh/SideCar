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

// 3666D97845F149E6B558D16F1030BB70
// 89apmCt7e-z_snmdrquiNvUtollIcYxEnEMpKQfgAkH7Msmq

// DECBE2A6A10343E58518A449A6F8BFC1
// LiYP-jdHPdf4yTTPpGlsxtAS01h3fUwHtr0hCfy0aqT8Vasd