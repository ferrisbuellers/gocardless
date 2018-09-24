<?php

namespace Midnite81\GoCardless\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Midnite81\GoCardless\Models\WebHook;

class SaveWebHookToDatabase
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

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $data = $event->getData();
        $ip = $event->getIp();
        $url = $event->getUrl();

        WebHook::create([
           'data' => $data,
           'ip' => $ip,
           'url' => $url,
        ]);
    }
}
