<?php

namespace App\Services;

use App\Exports\EdPExExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class EdPExService
{
    /**
     * Update the EdPEx.xlsx file in storage
     *
     * @return void
     */
    public static function updateExport()
    {
        try {
            // Generate the file and store it in storage/app/public/EdPEx.xlsx
            Excel::store(new EdPExExport(), 'EdPEx.xlsx', 'public');
        } catch (\Exception $e) {
            \Log::error('EdPEx Export Error: ' . $e->getMessage());
        }
    }
}
