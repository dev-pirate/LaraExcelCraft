<?php

if (! function_exists('laraExcel')) {
    function notify(string $message = null, string $title = null): LaravelNotify
    {
        $notify = app('notify');

        if (! is_null($message)) {
            return $notify->success($message, $title);
        }

        return $notify;
    }
}
