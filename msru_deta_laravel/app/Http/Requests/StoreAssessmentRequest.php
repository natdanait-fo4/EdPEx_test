<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
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
            'answers' => 'required|array',
            'suggestions' => 'nullable|array',
            'suggestions.*' => 'nullable|string',
            'major' => 'required|string'
        ];
    }
    
    public function messages(): array
    {
        return [
            'answers.required' => 'กรุณาทำแบบประเมินให้ครบถ้วน',
            'answers.array' => 'รูปแบบข้อมูลการประเมินไม่ถูกต้อง',
            'major.required' => 'กรุณาระบุสาขาวิชา',
        ];
    }
}
