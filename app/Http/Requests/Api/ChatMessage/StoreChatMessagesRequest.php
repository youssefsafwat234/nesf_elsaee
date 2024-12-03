<?php

namespace App\Http\Requests\Api\ChatMessage;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessagesRequest extends FormRequest
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
            'chat_id' => ['required', 'exists:chats,id'],
            'message' => ['required', 'string'],
            'type' => ['required', 'string', 'in:text,image,video,file'],
            'uploaded_files' => ['nullable', 'array'],
            'uploaded_files.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4,mov,ogg,webm,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,7z'],
        ];
    }
}
