<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use App\Models\Tenant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;

class TableFetchController
{
    public function __invoke() {
        $tables = $this->getTables();

        $tablesInfo = [];

        foreach ($tables as $table) {
            $fields = Schema::getColumnListing($table);

            $tablesInfo[$table] = $fields;
        }

        return response()->json(['tablesInfo' => $tablesInfo, 'success' => true]);
    }

    public function getTables()
    {
        $schemaManager = $this->getSchemaManager();
        $databaseName = $this->getDatabaseName();

        return $schemaManager->listTableNames($databaseName);
    }

    protected function getSchemaManager()
    {
        return app(Builder::class)->getConnection()->getDoctrineSchemaManager();
    }

    protected function getDatabaseName()
    {
        if (function_exists('tenant_db_finder')) {
            $dbname = tenant_db_finder();
            if ($dbname) return $dbname;
        }
        return config('database.connections.mysql.database');
    }
}
