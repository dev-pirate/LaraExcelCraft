<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class FileUploadController
{
    public function __invoke(Request $request) {
        try {
            $fileLocationDisk = config('lara-excel-craft.fileTempDisk');
            $file = $request->file('file');
            $fileName = basename(Storage::disk($fileLocationDisk)->put("laraExcelCraft/temp", $file));

            // Store metadata (timestamp for removal)
            $expirationTimestamp = now()->addSecond(50); // Adjust the time as needed
            Storage::put("laraExcelCraft/temp/$fileName" . '.meta', json_encode(['expiration' => $expirationTimestamp]));

            Session::put('imported_file_name', $fileName);

            return new JsonResponse([
                'success' => true,
                'fileName' => $fileName
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'success' => false,
                'fileName' => $fileName ?? ''
            ]);
        }

    }
}
