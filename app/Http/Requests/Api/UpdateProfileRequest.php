<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone,' . auth()->id()],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الرجاء إدخال الاسم.',
            'name.string' => 'الاسم يجب ان يكون نصي.',
            'name.max' => 'الاسم يجب ان يكون اقل من 255 حرف.',
            'email.required' => 'الرجاء إدخال البريد الالكتروني.',
            'email.string' => 'البريد الالكتروني يجب ان يكون نصي.',
            'email.email' => 'البريد الالكتروني يجب ان يكون بريد الكتروني صحيح.',
            'email.max' => 'البريد الالكتروني يجب ان يكون اقل من 255 حرف.',
            'email.unique' => 'البريد الالكتروني موجود مسبقا.',
            'phone.required' => 'الرجاء ادخال الهاتف.',
            'phone.string' => 'الهاتف يجب ان يكون نصي.',
            'phone.max' => 'الهاتف يجب ان يكون اقل من 255 حرف.',
            'phone.unique' => 'الهاتف موجود مسبقا.',
            'logo.image' => 'يجب أن يكون الشعار صورة.',
            'logo.mimes' => 'يجب أن يكون الشعار من نوع jpg, jpeg, png.',
        ];
    }
}
