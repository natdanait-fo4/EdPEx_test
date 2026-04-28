<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\EdPExService;

class RequestController extends Controller
{
    public function index()
    {
        if (request('login') != '1' && !auth()->check()) {
            return redirect(url('/?login=1'));
        }
        
        $history = UserRequest::where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('request.index', compact('history'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'details' => 'required|string',
        ]);

        UserRequest::create([
            'user_id' => Auth::id(),
            'category' => $request->category,
            'details' => $request->details,
            'status' => 'pending',
        ]);

        EdPExService::updateExport();

        return redirect()->route('request.index')->with('success', 'ส่งข้อมูลความต้องการของท่านเรียบร้อยแล้ว ขอบคุณที่ร่วมพัฒนาคณะฯ ไปกับเรา');
    }
}
