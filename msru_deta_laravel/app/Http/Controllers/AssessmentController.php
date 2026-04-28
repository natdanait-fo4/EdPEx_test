<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResponse;
use App\Models\AssessmentAnswer;
use App\Services\EdPExService;

class AssessmentController extends Controller
{
    public function index()
    {
        $allAnswers = AssessmentAnswer::all();
        $totalUsers = AssessmentResponse::count();
        $overallScore = 0;
        
        if ($totalUsers > 0 && $allAnswers->count() > 0) {
            $overallScore = round($allAnswers->avg('score'), 1);
        }
        
        $questions = AssessmentQuestion::orderBy('order')->get();
        $groupedQuestions = $questions->groupBy('category');
        
        return view('assessment.index', compact('totalUsers', 'overallScore', 'groupedQuestions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'suggestion' => 'nullable|string'
        ]);

        $response = AssessmentResponse::create([
            'suggestion' => $request->input('suggestion')
        ]);

        foreach ($request->input('answers') as $questionId => $score) {
            if ($score > 0) {
                AssessmentAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $questionId,
                    'score' => $score
                ]);
            }
        }
        
        EdPExService::updateExport();
        
        session()->put('has_assessed', true);
        return redirect()->back()->with('success', 'ขอบคุณที่ร่วมแสดงความคิดเห็นครับ');
    }
}
