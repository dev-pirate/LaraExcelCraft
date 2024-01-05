<?php

namespace DevPirate\LaraExcelCraft\Controllers;

use App\Models\Tenant\Example;
use App\Models\Tenant\Guest;
use DevPirate\LaraExcelCraft\Interfaces\ImportableInterface;
use DevPirate\LaraExcelCraft\LaraExcelCraft;
use DevPirate\LaraExcelCraft\Services\FileDataReader;
use DevPirate\LaraExcelCraft\Services\TableNamesFinder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImportController
{
    public function __invoke(Request $request, FileDataReader $service, TableNamesFinder $finder) {
        $data = $this->validator($request->all())->validate();

        $file = $data['file'];
        $columns = json_decode($data['columns']);
        $tableName = $data['tbN'];

        $tables = $finder->findTables();
        $filtered = array_filter($tables, function($value) use($tableName) {
            return $value['table_name'] === $tableName; // Find the first even number
        });
        $filtered = empty($filtered) ? null : reset($filtered);

        $modelClass = $filtered['class_name'];

        $data = [];
        $fileData = $service->getFileData($file);

        foreach ($fileData as $row) {
            $rowData = [];
            foreach ($columns as $key => $obj) {
                $fileDataItem = $row[$key];
                $rowData[$obj->value] = $fileDataItem;
            }
            $data[] = $rowData;
        }

        // Check if the class exists and if it uses the ImportableTrait
        if (class_exists($modelClass) && in_array(ImportableInterface::class, class_implements($modelClass))) {
            // Call the importData function in the dynamically determined class
            $modelClass::importDataFromExcel($data);

            return response()->json(['message' => 'Data imported successfully', "success" => true, 'redirectTo' => route(config('lara-excel-craft.redirectTo'))]);
        } else {
            $modelClass::insert($data);

            return response()->json(['message' => 'Data imported successfully', "success" => true, 'redirectTo' => route(config('lara-excel-craft.redirectTo'))]);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'file' => ['required', 'string'],
            'tbN' => ['required', 'string'],
            'columns' => ['required'],
        ]);
    }
}
