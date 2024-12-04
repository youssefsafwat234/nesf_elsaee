<?php

namespace App\Http\Requests\Api\Auction;

use Illuminate\Foundation\Http\FormRequest;
use Livewire\Attributes\Rule;

class StoreAuctionRequest extends FormRequest
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
            'video_path' => ['required', 'mimes:mp4,mov,ogg,qt'],
            'city_id' => ['required', 'exists:cities,id'],
            'type' => ['required', \Illuminate\Validation\Rule::in(['بيع', 'شراء'])],
            'area' => ['required', 'numeric'],
            'starting_date' => ['required', 'date_format:Y-m-d H:i:s', 'before:ending_date'],
            'ending_date' => ['required', 'date_format:Y-m-d H:i:s', 'after:starting_date'],
            'auction_link' => ['required', 'url'],
            'notes' => ['nullable', 'string'],
            'images' => ['required', 'array', 'max:5'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ];
    }
}
