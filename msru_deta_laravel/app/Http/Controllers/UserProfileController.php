<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ], [
            'fullname.required' => 'กรุณากรอกชื่อ-นามสกุล',
        ]);

        $data = $request->only(['fullname', 'phone', 'address']);

        $user->update($data);

        return back()->with('success', 'อัปเดตข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }
}
