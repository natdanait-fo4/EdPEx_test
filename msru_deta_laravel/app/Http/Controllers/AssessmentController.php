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
        
        $questions = AssessmentQuestion::withAvg('answers', 'score')->get();
        
        $questions = AssessmentQuestion::sortQuestions($questions);

        $groupedQuestions = $questions->groupBy('category');
        
        return view('assessment.index', compact('totalUsers', 'overallScore', 'groupedQuestions'));
    }

    public function store(\App\Http\Requests\StoreAssessmentRequest $request)
    {

        $suggestionsArray = $request->input('suggestions', []);
        $formattedSuggestions = [];
        foreach ($suggestionsArray as $secIndex => $text) {
            $trimmed = trim($text);
            if (!empty($trimmed)) {
                $formattedSuggestions[] = "ส่วนที่ {$secIndex}: {$trimmed}";
            }
        }
        $fullSuggestion = implode("\n", $formattedSuggestions);

        $response = AssessmentResponse::create([
            'suggestion' => $fullSuggestion ?: null,
            'ip_address' => $request->ip(),
            'major' => $request->input('major'),
            'user_id' => auth()->check() ? auth()->id() : null,
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
        if (auth()->check()) {
            auth()->user()->update(['has_assessed' => true]);
        }
        
        if ($request->has('from_logout') || $request->input('from_logout')) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return view('auth.exit');
        }
        
        return redirect()->back()->with('success', 'ขอบคุณที่ร่วมแสดงความคิดเห็นครับ');
    }
}
