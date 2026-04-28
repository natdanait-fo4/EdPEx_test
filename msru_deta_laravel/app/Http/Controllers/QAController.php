<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Question;
use App\Services\EdPExService;

class QAController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        $user_questions = Question::with('user')
                                  ->where('status', 'public')
                                  ->orderBy('created_at', 'DESC')
                                  ->get();
        $my_questions = auth()->check() ? Question::where('user_id', auth()->id())->orderBy('created_at', 'DESC')->get() : collect([]);

        return view('qa.index', compact('faqs', 'user_questions', 'my_questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        Question::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'details' => $request->details,
            'status' => 'waiting',
            'privacy' => $request->privacy ?? 'public',
        ]);

        EdPExService::updateExport();

        return redirect()->route('qa.index')->with('success', 'ส่งคำถามเรียบร้อยแล้ว รอการตรวจสอบและการตอบกลับจากแอดมิน');
    }

    public function destroy($id)
    {
        $question = \App\Models\Question::findOrFail($id);
        
        if (auth()->check() && auth()->id() === $question->user_id) {
            $question->delete();
            return back()->with('success', 'ลบคำถามเรียบร้อยแล้ว');
        }
        
        return back()->with('error', 'ไม่มีสิทธิ์ในการลบคำถามนี้');
    }
}
