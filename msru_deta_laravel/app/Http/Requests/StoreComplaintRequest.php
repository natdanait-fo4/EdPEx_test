<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }
    
    public function messages(): array
    {
        return [
            'topic.required' => 'กรุณาระบุหัวข้อเรื่อง',
            'description.required' => 'กรุณาระบุรายละเอียด',
            'file.mimes' => 'ไฟล์แนบต้องเป็นนามสกุล jpg, jpeg, png, pdf เท่านั้น',
            'file.max' => 'ขนาดไฟล์ต้องไม่เกิน 5MB',
        ];
    }
}
