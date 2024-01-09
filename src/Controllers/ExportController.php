<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use DevPirate\LaraExcelCraft\Interfaces\ImportableInterface;
use DevPirate\LaraExcelCraft\LaraExcelCraft;
use DevPirate\LaraExcelCraft\Services\FileDataReader;
use DevPirate\LaraExcelCraft\Services\TableNamesFinder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExportController
{
    public function __invoke(Request $request, FileDataReader $service, TableNamesFinder $finder) {
        $data = $this->validator($request->all())->validate();

        $columns = json_decode($data['columns']);
        $tableName = $data['tbN'];

        $tables = $finder->findTables();
        $filtered = array_filter($tables, function($value) use($tableName) {
            return $value['table_name'] === $tableName; // Find the first even number
        });
        $filtered = empty($filtered) ? null : reset($filtered);

        $this->exportCsv($filtered['table_name'], $filtered['class_name'], $columns);

        return response()->json(['success' => true, 'redirectTo' => route(config('lara-excel-craft.redirectTo'))]);

        // Check if the class exists and if it uses the ImportableTrait
        /*if (class_exists($modelClass) && in_array(ImportableInterface::class, class_implements($modelClass))) {
            // Call the importData function in the dynamically determined class
            $modelClass::importDataFromExcel($data);

            return response()->json(['message' => 'Data imported successfully', "success" => true, 'redirectTo' => route(config('lara-excel-craft.redirectTo'))]);
        } else {
            $modelClass::insert($data);

            return response()->json(['message' => 'Data imported successfully', "success" => true, 'redirectTo' => route(config('lara-excel-craft.redirectTo'))]);
        }*/
    }

    public function exportCsv($table, $className, $columns)
    {
        $data = $className::all(); // Replace with your query to fetch data

        $csvFileName = $table. '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $handle = fopen('php://output', 'w');

        // Add CSV headers
        fputcsv($handle, array_keys($data[0]->toArray()));

        // Add data rows
        foreach ($data as $row) {
            $array = [];
            foreach ($columns as $exportableColumn) {
                $array[$exportableColumn] = $row->$exportableColumn;
            }
            fputcsv($handle, $array);
        }

        fclose($handle);

        return Response::make(file_get_contents('php://output'), 200, $headers);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tbN' => ['required', 'string'],
            'columns' => ['required'],
        ]);
    }
}
