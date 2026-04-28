<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EdPExService;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function exportEdPEx()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EdPExExport(), 'EdPEx.xlsx');
    }

    public function generateEdPEx()
    {
        EdPExService::updateExport();
        return redirect()->back()->with('success', 'ปรับปรุงไฟล์ข้อมูล EdPEx เรียบร้อยแล้ว');
    }
}
