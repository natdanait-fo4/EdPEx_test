<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Services\EdPExService;

class ComplaintController extends Controller
{
    public function index()
    {
        if (request('login') != '1' && !auth()->check()) {
            return redirect(url('/?login=1'));
        }
        
        $complaints = auth()->check() 
            ? Complaint::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get() 
            : collect();

        return view('complaint.index', compact('complaints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('complaints', 'public');
        }

        Complaint::create([
            'user_id' => auth()->id(),
            'topic' => $request->topic,
            'description' => $request->description,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        EdPExService::updateExport();

        return redirect()->back()->with('success', 'ส่งเรื่องร้องเรียนเรียบร้อยแล้ว');
    }
}
