<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EdPExExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new AssessmentsExport(),
            new ComplaintsExport(),
            new RequestsExport(),
            new QuestionsExport(),
        ];
    }
}
