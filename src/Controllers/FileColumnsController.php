<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use DevPirate\LaraExcelCraft\Interfaces\ExcelManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;

class FileColumnsController
{
    public function __invoke(Request $request, $fileName) {
        $fileLocationDisk = config('lara-excel-craft.fileTempDisk');

        // Build the file path in the storage directory
        $filePath = Storage::disk($fileLocationDisk)->path("laraExcelCraft/temp/$fileName");

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found', 'success' => false], 404);
        }

        // Load the Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Get the highest column number (e.g., 'F' for the 6th column)
        $highestColumn = $worksheet->getHighestColumn();

        // Convert the highest column letter to a column index
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Retrieve column headers from the first row
        $columns = [];
        foreach ($worksheet->getRowIterator(1,1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $columnName = $cell->getColumn();
                $cellValue = $cell->getValue();

                $columns[] = [
                    'title' => $cellValue,
                    'key' => $columnName
                ];
            }
        }

        // Return the columns as JSON response
        return response()->json(['columns' => $columns, 'success' => true]);
    }

    function getFileData($worksheet) {
        $dataToInsert = [];
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = [];

            // Iterate through cells and use letters (A, B, C, ...) as column names
            foreach ($row->getCellIterator() as $cell) {
                $columnName = $cell->getColumn();
                $cellValue = $cell->getValue();
                $rowData[$columnName] = $cellValue;
            }

            // Add the row data to the array
            $dataToInsert[] = $rowData;
        }

        return $dataToInsert;

    }

    function saveData($data, $tableName) {
        // Dynamically determine the model class based on the table name
        $modelClass = 'App\\Models\\' . ucfirst(Str::camel($tableName));

        // Check if the class exists and if it uses the ImportableTrait
        if (class_exists($modelClass) && in_array(ExcelManager::class, class_implements($modelClass))) {
            // Call the importData function in the dynamically determined class
            $modelClass::importData($data);

            return response()->json(['message' => 'Data imported successfully']);
        }
    }
}
