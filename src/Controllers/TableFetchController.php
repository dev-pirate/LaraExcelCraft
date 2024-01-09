<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use App\Models\Tenant;
use DevPirate\LaraExcelCraft\Interfaces\ImportableInterface;
use DevPirate\LaraExcelCraft\Services\TableNamesFinder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;

class TableFetchController
{
    public function __invoke(TableNamesFinder $finder) {
        $tables = $finder->findTables();

        $tablesInfo = [];

        foreach ($tables as $table) {
            $modelClass = $table['class_name'];
            $tableName = $table['table_name'];
            if (class_exists($modelClass) && in_array(ImportableInterface::class, class_implements($modelClass))) {
                $fields = $modelClass::getImportableFields();
            } else {
                $fields = Schema::getColumnListing($tableName);
            }

            $tablesInfo[$tableName] = $fields;
        }

        return response()->json(['tablesInfo' => $tablesInfo, 'success' => true]);
    }
}
