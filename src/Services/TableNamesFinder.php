<?php

namespace DevPirate\LaraExcelCraft\Services;

use DevPirate\LaraExcelCraft\Interfaces\ExcelManager;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class TableNamesFinder
{
    public function findTables() {
        $modelPath = config('lara-excel-craft.models_path');
        $files = (new Filesystem)->files($modelPath);

        $modelInfo = [];

        foreach ($files as $file) {
            $content = file_get_contents($file->getPathname());

            // Use regular expression to extract namespace and class name
            preg_match('/namespace (.+?);.*?class (.+?) /s', $content, $matches);

            if (count($matches) === 3) {
                $namespace = $matches[1];
                $className = $matches[2];

                // Check if the $table property is defined in the model
                if (strpos($content, '$table') !== false) {
                    preg_match('/protected\s+\$table\s*=\s*[\'"](.*?)[\'"]/', $content, $tableMatches);
                    $tableName = isset($tableMatches[1]) ? $tableMatches[1] : Str::snake(Str::plural($className));
                } else {
                    // Convert class name to table name using Laravel's naming conventions
                    $tableName = Str::snake(Str::plural($className));
                }
                $classNamespace = $namespace . '\\' . $className;
                if (class_exists($classNamespace) && in_array(ExcelManager::class, class_implements($classNamespace))) {
                    $modelInfo[] = [
                        'class_name' => $classNamespace,
                        'table_name' => $tableName,
                    ];
                }
            }
        }

        // Output the result
        return $modelInfo;
    }
}
