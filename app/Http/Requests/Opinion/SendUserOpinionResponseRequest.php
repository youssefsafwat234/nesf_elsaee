<?php

namespace App\Http\Requests\Opinion;

use Illuminate\Foundation\Http\FormRequest;

class SendUserOpinionResponseRequest extends FormRequest
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
            'opinion_id' => 'required|exists:opinions,id',
            'view_status' => 'required|boolean',
            'satisfy_status' => 'required|boolean',
            'content' => 'sometimes',
        ];
    }

    public function messages(): array
    {
        return [
            'opinion_id.required' => 'من فضلك ارسل رقم الاستطلاع',
            'opinion_id.exists' => 'الاستطلاع غير موجود',
            'view_status.required' => 'من فضلك ارسل إجابة السؤال (هل رأيت العقار)',
            'view_status.boolean' => 'حالة السؤال يجب ان تكون نعم ام لا ',
            'satisfy_status.required' => 'من فضلك ارسل إجابة السؤال (هل انت راضى عن العقار)',
            'satisfy_status.boolean' => 'حالة السؤال يجب ان تكون نعم ام لا ',
        ];
    }
}
