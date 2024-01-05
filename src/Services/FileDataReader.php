<?php

namespace DevPirate\LaraExcelCraft\Services;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileDataReader
{
    public function getFileData($fileName) {
        $fileLocationDisk = config('lara-excel-craft.fileTempDisk');

        // Build the file path in the storage directory
        $filePath = Storage::disk($fileLocationDisk)->path("laraExcelCraft/temp/$fileName");

        // Check if the file exists
        if (!file_exists($filePath)) {
            return [];
        }

        // Load the Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        $dataToInsert = [];
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = [];

            // Iterate through cells and use letters (A, B, C, ...) as column names
            foreach ($row->getCellIterator() as $cell) {
                $columnName = $cell->getColumn();
                $cellValue = $cell->getFormattedValue();
                $rowData[$columnName] = $cellValue;
            }

            // Add the row data to the array
            $dataToInsert[] = $rowData;
        }

        return $dataToInsert;
    }
}
