<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as UserRequest;

class AdminRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = UserRequest::with('user')->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(10);

        $stats = [
            'pending' => UserRequest::where('status', 'pending')->count(),
            'processing' => UserRequest::where('status', 'processing')->count(),
            'completed' => UserRequest::where('status', 'completed')->count(),
        ];

        return view('admin.requests.index', compact('requests', 'stats', 'status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
            'reply' => 'nullable|string',
        ]);

        $req = UserRequest::findOrFail($id);
        $req->update([
            'status' => $request->status,
            'reply' => $request->reply,
        ]);

        return redirect()->back()->with('success', 'อัปเดตสถานะและบันทึกการตอบกลับเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $req = UserRequest::findOrFail($id);
        $req->delete();

        return redirect()->back()->with('success', 'ลบรายการความต้องการเรียบร้อยแล้ว');
    }
}
