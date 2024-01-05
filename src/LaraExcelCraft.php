<?php

namespace DevPirate\LaraExcelCraft;

class LaraExcelCraft
{
    public function greet(String $sName)
    {
        return 'Hi ' . $sName . '! How are you doing today?';
    }

    public static function routes()
    {
        require __DIR__.'/../routes/web.php';
    }
}
