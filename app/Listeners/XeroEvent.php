<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Webfox\Xero\Events\XeroAuthorized;
class XeroEvent
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(XeroAuthorized $event)
    {
        Log::info(json_encode($event));
    }
}
