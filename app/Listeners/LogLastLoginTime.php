<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogLastLoginTime
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $caller = $trace[1]['file'] . ':' . $trace[1]['line'];
        
        // Log the caller information
        logger()->info('Authenticated event triggered from: ' . $caller);
        $event->user->loginLogs()->create(['login' => now()]);
    }
}