<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionsExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return Question::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'วันที่',
            'ผู้ถาม',
            'หัวข้อ',
            'รายละเอียด',
            'สถานะ',
            'ความเป็นส่วนตัว'
        ];
    }

    public function title(): string
    {
        return 'ถาม-ตอบ (Q&A)';
    }

    public function map($question): array
    {
        return [
            $question->id,
            $question->created_at->format('Y-m-d H:i:s'),
            $question->user->name ?? 'ไม่ระบุตัวตน',
            $question->title,
            $question->details,
            $question->status,
            $question->privacy
        ];
    }
}
