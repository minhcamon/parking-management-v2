<?php

if (!function_exists('toast')) {
    /**
     * Flash a toast message to the session.
     *
     * @param string $message
     * @param string $type success|error|warning|info
     * @return void
     */
    function toast($message, $type = 'success')
    {
        session()->flash("toast_{$type}", $message);
    }
}
