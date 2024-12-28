<?php

namespace App\Http\Requests\Opinion;

use Illuminate\Foundation\Http\FormRequest;

class SendOpinionRequest extends FormRequest
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
            'receiver_id' => 'required|exists:users,id',
            'advertisement_id' => 'required|exists:advertisements,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'receiver_id.required' => 'من فضلك ارسل المستخدم',
            'receiver_id.exists' => 'المستخدم غير موجود',
            'advertisement_id.required' => 'من فضلك ارسل الإعلان',
            'advertisement_id.exists' => 'الإعلان غير موجود',
        ];
    }
}
