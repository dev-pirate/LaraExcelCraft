<?php

use DevPirate\LaraExcelCraft\Controllers\FileUploadController;
use DevPirate\LaraExcelCraft\Controllers\TableFetchController;
use DevPirate\LaraExcelCraft\Controllers\FileColumnsController;
use DevPirate\LaraExcelCraft\Controllers\FileDataController;
use DevPirate\LaraExcelCraft\Controllers\ImportController;
use DevPirate\LaraExcelCraft\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'lara-excel-craft'], function () {
    Route::post('file-import', FileUploadController::class)->name('lara_excel_craft.file_import');
    Route::get('table-map', TableFetchController::class)->name('lara_excel_craft.table_fetch');
    Route::get('{fileName}/file-columns', FileColumnsController::class)->name('lara_excel_craft.file_columns');
    Route::get('{fileName}/file-data', FileDataController::class)->name('lara_excel_craft.file_data');
    Route::post('excel-data-import', ImportController::class)->name('lara_excel_craft.excel_confirm_import');
    Route::post('excel-data-export', ExportController::class)->name('lara_excel_craft.excel_export');

});

