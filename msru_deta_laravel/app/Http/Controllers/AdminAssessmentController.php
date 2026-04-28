<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResponse;
use App\Models\AssessmentAnswer;

class AdminAssessmentController extends Controller
{
    public function index()
    {
        $questions = AssessmentQuestion::orderBy('order')->get();
        return view('admin.assessment.index', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category' => 'nullable|string',
            'order' => 'integer'
        ]);

        AssessmentQuestion::create($request->all());
        return redirect()->back()->with('success', 'เพิ่มหัวข้อประเมินเรียบร้อยแล้ว');
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category' => 'nullable|string',
            'order' => 'integer'
        ]);

        $question = AssessmentQuestion::findOrFail($id);
        $question->update($request->all());
        return redirect()->back()->with('success', 'แก้ไขหัวข้อประเมินเรียบร้อยแล้ว');
    }

    public function destroyQuestion($id)
    {
        AssessmentQuestion::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'ลบหัวข้อประเมินเรียบร้อยแล้ว');
    }

    public function responses()
    {
        $responses = AssessmentResponse::with('answers.question')->latest()->get();
        $questions = AssessmentQuestion::orderBy('order')->get();
        return view('admin.assessment.responses', compact('responses', 'questions'));
    }

    public function destroyResponse($id)
    {
        AssessmentResponse::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลการประเมินเรียบร้อยแล้ว');
    }

    /**
     * Export assessment responses to Excel (CSV format)
     */
    public function exportExcel()
    {
        $questions = AssessmentQuestion::orderBy('order')->get();
        $responses = AssessmentResponse::with('answers')->latest()->get();

        $filename = "assessment_responses_" . now()->format('Y-m-d_H-i-s') . ".csv";

        return response()->streamDownload(function() use ($questions, $responses) {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Thai characters in Excel
            fputs($handle, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Header Row 1: Categories
            $header1 = ['ลำดับ', 'วันที่ส่ง'];
            $categories = $questions->groupBy('category');
            foreach ($categories as $category => $categoryQuestions) {
                $header1[] = $category ?: 'ทั่วไป';
                // Pad for colspan
                for ($i = 1; $i < $categoryQuestions->count(); $i++) {
                    $header1[] = '';
                }
            }
            $header1[] = 'ข้อเสนอแนะ';
            fputcsv($handle, $header1);

            // Header Row 2: Question IDs/Texts
            $header2 = ['', ''];
            foreach ($questions as $index => $question) {
                $header2[] = 'Q' . ($index + 1) . ': ' . $question->question_text;
            }
            $header2[] = '';
            fputcsv($handle, $header2);

            // Data Rows
            foreach ($responses as $index => $response) {
                $row = [
                    $index + 1,
                    $response->created_at->format('Y-m-d H:i:s'),
                ];

                $answerMap = $response->answers->pluck('score', 'question_id')->toArray();
                foreach ($questions as $question) {
                    $row[] = $answerMap[$question->id] ?? '-';
                }

                $row[] = $response->suggestion;
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }
}
