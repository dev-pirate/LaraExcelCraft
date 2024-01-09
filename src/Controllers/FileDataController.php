<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use DevPirate\LaraExcelCraft\Interfaces\ExcelManager;
use DevPirate\LaraExcelCraft\Services\FileDataReader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;

class FileDataController
{
    public function __invoke(Request $request, FileDataReader $service, $fileName) {
        $dataToInsert = $service->getFileData($fileName);

        // Return the columns as JSON response
        return response()->json(['rows' => $dataToInsert, 'success' => true]);
    }
}
