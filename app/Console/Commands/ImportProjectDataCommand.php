<?php

namespace App\Console\Commands;

use App\Modules\Import\ImportProjectData\ImportProjectData;
use Illuminate\Console\Command;

class ImportProjectDataCommand extends Command
{
    protected $signature = 'import:project-data';

    protected $description = 'Import Project Data -> Users, Roles and Branch offices';


    public function handle(): int
    {
        /** @var ImportProjectData $import */
        $import = app(ImportProjectData::class);

        $import->import();

        $this->info('Project Data Imported Successfully!');
        return Command::SUCCESS;
    }
}
