<?php

use DevPirate\LaraExcelCraft\Controllers\FileUploadController;
use DevPirate\LaraExcelCraft\Controllers\TableFetchController;
use DevPirate\LaraExcelCraft\Controllers\FileColumnsController;
use DevPirate\LaraExcelCraft\Controllers\FileDataController;
use DevPirate\LaraExcelCraft\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'lara-excel-craft'], function () {
    Route::post('file-import', FileUploadController::class);
    Route::get('table-map', TableFetchController::class);
    Route::get('{fileName}/file-columns', FileColumnsController::class);
    Route::get('{fileName}/file-data', FileDataController::class);
    Route::post('excel-data-import', ImportController::class);
});

