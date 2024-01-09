<?php

namespace DevPirate\LaraExcelCraft\Interfaces;

interface ImportableInterface
{
    public static function importDataFromExcel(array $data);

    public static function getImportableFields();

    public static function exportDataFromExcel();
}
