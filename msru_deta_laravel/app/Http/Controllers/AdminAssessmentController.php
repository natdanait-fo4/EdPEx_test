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
        $questions = AssessmentQuestion::get();
        $questions = AssessmentQuestion::sortQuestions($questions);
        return view('admin.assessment.index', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category' => 'nullable|string',
        ]);

        $category = $request->input('category');
        $maxOrder = AssessmentQuestion::where('category', $category)->max('order') ?? 0;

        AssessmentQuestion::create([
            'question_text' => $request->question_text,
            'category' => $category,
            'order' => $maxOrder + 1
        ]);

        return redirect()->back()->with('success', 'เพิ่มหัวข้อประเมินเรียบร้อยแล้ว');
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category' => 'nullable|string',
        ]);

        $question = AssessmentQuestion::findOrFail($id);
        $newCategory = $request->input('category');

        if ($question->category !== $newCategory) {
            $maxOrder = AssessmentQuestion::where('category', $newCategory)->max('order') ?? 0;
            $question->order = $maxOrder + 1;
        }

        $question->question_text = $request->question_text;
        $question->category = $newCategory;
        $question->save();

        return redirect()->back()->with('success', 'แก้ไขหัวข้อประเมินเรียบร้อยแล้ว');
    }

    public function destroyQuestion($id)
    {
        AssessmentQuestion::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'ลบหัวข้อประเมินเรียบร้อยแล้ว');
    }

    public function responses(Request $request)
    {
        $query = AssessmentResponse::with(['answers.question', 'user'])->latest();
        
        $selectedMajor = $request->query('major');
        if ($selectedMajor) {
            $query->where('major', $selectedMajor);
        }

        $selectedEmail = $request->query('email');
        if ($selectedEmail) {
            $query->whereHas('user', function ($q) use ($selectedEmail) {
                $q->where('email', 'like', '%' . $selectedEmail . '%');
            });
        }
        
        $responses = $query->get();
        $questions = AssessmentQuestion::get();
        $questions = AssessmentQuestion::sortQuestions($questions);
        
        $majors = [
            'สาขาการบัญชี',
            'สาขาการตลาดดิจิทัล',
            'สาขาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ',
            'สาขาคอมพิวเตอร์ธุรกิจ / ธุรกิจดิจิทัลและเทคโนโลยี',
            'สาขาการท่องเที่ยวและการโรงแรม',
            'สาขานิเทศศาสตร์',
            'สาขาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่'
        ];
        
        return view('admin.assessment.responses', compact('responses', 'questions', 'majors', 'selectedMajor', 'selectedEmail'));
    }
 
    public function destroyResponse($id)
    {
        $response = AssessmentResponse::findOrFail($id);
        if ($response->user_id) {
            $user = \App\Models\User::find($response->user_id);
            if ($user) {
                // ตรวจสอบว่ายังมีประวัติการประเมินอันอื่นของคนนี้เหลืออยู่หรือไม่
                $remainingCount = AssessmentResponse::where('user_id', $response->user_id)
                                                    ->where('id', '!=', $response->id)
                                                    ->count();
                if ($remainingCount === 0) {
                    $user->update(['has_assessed' => false]);
                }
            }
        }
        $response->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลการประเมินเรียบร้อยแล้ว');
    }
 
    /**
     * Export assessment responses to Excel (CSV format)
     */
    public function exportExcel(Request $request)
    {
        $questions = AssessmentQuestion::get();
        $questions = AssessmentQuestion::sortQuestions($questions);
        
        $query = AssessmentResponse::with(['answers', 'user'])->latest();
        
        $selectedMajor = $request->query('major');
        if ($selectedMajor) {
            $query->where('major', $selectedMajor);
        }

        $selectedEmail = $request->query('email');
        if ($selectedEmail) {
            $query->whereHas('user', function ($q) use ($selectedEmail) {
                $q->where('email', 'like', '%' . $selectedEmail . '%');
            });
        }

        $responses = $query->get();
 
        $filename = "assessment_responses_" . ($selectedEmail ? str_replace('@', '_at_', $selectedEmail) . "_" : "") . ($selectedMajor ? str_replace([' / ', ' '], '_', $selectedMajor) . "_" : "") . now()->format('Y-m-d_H-i-s') . ".csv";
 
        return response()->streamDownload(function() use ($questions, $responses) {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Thai characters in Excel
            fputs($handle, (chr(0xEF) . chr(0xBB) . chr(0xBF)));
 
            // Header Row 1: Categories
            $header1 = ['ลำดับ', 'วันที่ส่ง', 'ผู้ส่ง (อีเมล)', 'สาขา'];
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
            $header2 = ['', '', '', ''];
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
                    $response->user ? $response->user->email : 'ผู้เยี่ยมชม (Guest)',
                    $response->major ?: '-',
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

    public function updateCategory(Request $request)
    {
        $request->validate([
            'old_category' => 'required|string',
            'new_category' => 'required|string',
        ]);

        $oldCategory = $request->input('old_category');
        $newCategory = $request->input('new_category');

        AssessmentQuestion::where('category', $oldCategory)
            ->update(['category' => $newCategory]);

        return redirect()->back()->with('success', 'แก้ไขชื่อส่วนเรียบร้อยแล้ว');
    }
}
