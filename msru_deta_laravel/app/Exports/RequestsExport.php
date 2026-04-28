<?php

namespace App\Exports;

use App\Models\Request as UserRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class RequestsExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return UserRequest::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'วันที่',
            'ผู้แจ้ง',
            'หมวดหมู่',
            'รายละเอียด',
            'สถานะ'
        ];
    }

    public function title(): string
    {
        return 'ความต้องการ (Requests)';
    }

    public function map($request): array
    {
        return [
            $request->id,
            $request->created_at->format('Y-m-d H:i:s'),
            $request->user->name ?? 'ไม่ระบุตัวตน',
            $request->category,
            $request->details,
            $request->status
        ];
    }
}
