<?php

namespace DevPirate\LaraExcelCraft\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveExpiredFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to remove temp files based on meta data expiration time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileLocationDisk = config('lara-excel-craft.fileTempDisk');

        $files = Storage::disk($fileLocationDisk)->allFiles('laraExcelCraft/temp');

        foreach ($files as $file) {
            $metaFile = $file . '.meta';

            if (Storage::exists($metaFile)) {
                $metadata = json_decode(Storage::get($metaFile), true);

                if (isset($metadata['expiration']) && now()->gt($metadata['expiration'])) {
                    Storage::delete($file);
                    Storage::delete($metaFile);
                }
            }
        }

        return Command::SUCCESS;
    }
}
