<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComplaintsExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return Complaint::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'วันที่',
            'ผู้แจ้ง',
            'หัวข้อ',
            'รายละเอียด',
            'สถานะ',
            'การตอบกลับ'
        ];
    }

    public function title(): string
    {
        return 'ข้อร้องเรียน (Complaints)';
    }

    public function map($complaint): array
    {
        return [
            $complaint->id,
            $complaint->created_at->format('Y-m-d H:i:s'),
            $complaint->user->name ?? 'ไม่ระบุตัวตน',
            $complaint->topic,
            $complaint->description,
            $complaint->status,
            $complaint->reply ?? '-'
        ];
    }
}
