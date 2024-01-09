<?php

namespace DevPirate\LaraExcelCraft\Interfaces;

interface ImportableInterface
{
    /**
     * @param array $data
     * function that will be executed to save the Excel file rows ($data)
     * make sure that you add your logic here
     * the $data param keys are the table same fields
     */
    public static function importDataFromExcel(array $data): void;

    /**
     * @return array
     * return an array of fields that could be inserted from the imported Excel file
     */
    public static function getImportableFields(): array;

    /**
     * @return void
     * function that will be executed to return the exported rows from the db table
     * make sure that you add your logic here
     * make sure to use the table field names as keys for the array
     */
    public static function exportDataFromExcel(): array;
}
