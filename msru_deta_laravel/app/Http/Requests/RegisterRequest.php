<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'ends_with:@nsru.ac.th', 'unique:users'],
            'otp_code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'fullname' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'size:11', 'unique:users,student_id'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
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
        ];
    }
}
