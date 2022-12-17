<?php

namespace App\Modules\Import\ImportProjectData;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use stdClass;

class ImportProjectData
{
    public function __construct(
        protected readonly Excel               $excel,
        protected readonly ProjectDataMapUsers $mapUser,
    )
    {
    }

    // @TODO WIP import users
    public function import(): void
    {
        $users = $this->getUsers();
    }

    public function getUsers(): Collection
    {
        $data = $this->excel->toCollection(new stdClass, 'project-data/dados-do-projeto.xlsx');
        $sheet = $data->first();
        return $this->mapUser->map($sheet);
    }
}
