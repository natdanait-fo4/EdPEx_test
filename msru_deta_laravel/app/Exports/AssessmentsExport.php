<?php

namespace App\Exports;

use App\Models\AssessmentAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssessmentsExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return AssessmentAnswer::with(['response', 'question'])->get();
    }

    public function headings(): array
    {
        return [
            'ID การประเมิน',
            'วันที่',
            'หมวดหมู่',
            'คำถาม',
            'คะแนน',
            'ข้อเสนอแนะ'
        ];
    }

    public function title(): string
    {
        return 'การประเมิน (Assessments)';
    }

    public function map($answer): array
    {
        return [
            $answer->response_id,
            $answer->created_at->format('Y-m-d H:i:s'),
            $answer->question->category ?? '-',
            $answer->question->question_text,
            $answer->score,
            $answer->response->suggestion ?? '-'
        ];
    }
}
