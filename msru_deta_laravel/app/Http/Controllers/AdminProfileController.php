<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Show the form for changing the password.
     */
    public function showChangePasswordForm()
    {
        return view('admin.profile.change_password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
            'password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร และต้องประกอบด้วยตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก และตัวเลข',
            'password.confirmed' => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
        ]);

        $user = auth()->user();
        $user->password = $request->password;
        $user->save();

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
}
