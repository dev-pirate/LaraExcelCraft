<?php

namespace DevPirate\LaraExcelCraft\Facades;

use Illuminate\Support\Facades\Facade;

final class LaraExcelCraft extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'lara_excel_craft';
    }
}
