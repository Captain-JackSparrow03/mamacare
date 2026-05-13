<?php

namespace App\Helpers;

class Flash
{
    public static function success($message)
    {
        session()->flash('flash', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    public static function error($message)
    {
        session()->flash('flash', [
            'type' => 'error',
            'message' => $message
        ]);
    }

    public static function warning($message)
    {
        session()->flash('flash', [
            'type' => 'warning',
            'message' => $message
        ]);
    }

    public static function info($message)
    {
        session()->flash('flash', [
            'type' => 'info',
            'message' => $message
        ]);
    }
}