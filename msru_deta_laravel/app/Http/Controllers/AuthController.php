<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // แยกทางเดินระหว่าง Admin และ User ปกติ
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('username');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'ends_with:@nsru.ac.th', 'unique:users'],
            'otp_code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'fullname' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'size:11', 'unique:users,student_id'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ], [
            'email.required' => 'กรุณากรอกอีเมลนักศึกษา',
            'email.unique' => 'อีเมลนี้ถูกใช้งานไปแล้ว',
            'email.ends_with' => 'กรุณาใช้เมลของมหาวิทยาลัย (@nsru.ac.th) เท่านั้น',
            'otp_code.required' => 'กรุณากรอกรหัส OTP',
            'otp_code.size' => 'รหัส OTP ต้องมี 6 หลัก',
            'password.min' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
            'fullname.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'student_id.required' => 'กรุณากรอกรหัสนักศึกษา',
            'student_id.size' => 'รหัสนักศึกษาต้องมี 11 หลัก',
            'student_id.unique' => 'รหัสนักศึกษานี้ถูกใช้ลงทะเบียนไปแล้ว',
        ]);

        // Check OTP from Session
        $otpData = session('register_otp');

        if (!$otpData || $otpData['email'] !== $request->email || $otpData['code'] != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'รหัส OTP ไม่ถูกต้อง'])->withInput();
        }

        if (now()->isAfter($otpData['expires_at'])) {
            return back()->withErrors(['otp_code' => 'รหัส OTP หมดอายุแล้ว กรุณากดส่งใหม่'])->withInput();
        }

        \App\Models\User::create([
            'username' => $request->email, // Use email as username
            'email' => $request->email,
            'password' => $request->password, // Model casting hashes it
            'fullname' => $request->fullname,
            'student_id' => $request->student_id,
            'major' => $request->major,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'user',
        ]);

        // Clear OTP Session after success
        session()->forget('register_otp');

        return redirect()->route('login')->with('success', 'สมัครสมาชิกเรียบร้อยแล้ว กรุณาใช้เมลนักศึกษาเพื่อเข้าสู่ระบบ');
    }
}
