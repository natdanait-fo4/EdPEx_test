<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = Complaint::with('user')->orderBy('created_at', 'desc');

        if ($status && in_array($status, ['pending', 'processing', 'completed'])) {
            $query->where('status', $status);
        }

        $complaints = $query->paginate(20);

        // Stats for widgets
        $stats = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'processing' => Complaint::where('status', 'processing')->count(),
            'completed' => Complaint::where('status', 'completed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'status', 'stats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
            'reply' => 'nullable|string',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'status' => $request->status,
            'reply' => $request->reply,
        ]);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลเรื่องร้องเรียนเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรื่องร้องเรียนเรียบร้อยแล้ว');
    }
}
