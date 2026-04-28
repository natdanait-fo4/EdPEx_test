<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Question;

class AdminQAController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::orderBy('id', 'DESC')->get();
        
        $status = $request->get('status');
        $date = $request->get('date');
        $query = Question::orderBy('created_at', 'DESC');
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        } elseif ($status === 'all' || $date) {
            // Show all or filtered by date
        } else {
            $query->limit(20);
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        $user_questions = $query->get();
        return view('admin.qa.index', compact('faqs', 'user_questions', 'status', 'date'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string'
        ]);
        
        Faq::create($request->only('question', 'answer', 'category'));
        return redirect()->route('admin.qa.index')->with('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
    }

    public function updateFaq(Request $request)
    {
        $faq = Faq::findOrFail($request->id);
        $faq->update($request->only('question', 'answer', 'category'));
        return redirect()->route('admin.qa.index')->with('success', 'อัปเดตคำถามเรียบร้อยแล้ว');
    }

    public function destroyFaq(Request $request)
    {
        Faq::findOrFail($request->id)->delete();
        return redirect()->route('admin.qa.index')->with('success', 'ลบคำถามเรียบร้อยแล้ว');
    }

    public function updateStatus(Request $request)
    {
        $question = Question::findOrFail($request->q_id);
        $question->update(['status' => $request->status]);
        return redirect()->route('admin.qa.index')->with('success', 'อัปเดตสถานะคำถามเรียบร้อยแล้ว');
    }

    public function replyQuestion(Request $request)
    {
        $question = Question::findOrFail($request->q_id);
        $question->update([
            'answer' => $request->answer,
            'status' => 'answered'
        ]);
        return redirect()->route('admin.qa.index')->with('success', 'บันทึกคำตอบเรียบร้อยแล้ว');
    }

    public function destroyQuestion(Request $request)
    {
        Question::findOrFail($request->id)->delete();
        return redirect()->route('admin.qa.index')->with('success', 'ลบคำถามเรียบร้อยแล้ว');
    }
}
