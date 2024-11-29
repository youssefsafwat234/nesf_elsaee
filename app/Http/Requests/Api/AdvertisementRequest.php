<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
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

            'type' => ['required', 'in:إيجار,شراء'],
            'category_id' => ['required', 'exists:categories,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'neighbourhood_id' => ['required', 'exists:neighbourhoods,id'],
            'location' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],


            // advertisement images
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:8192',

            // Area fields
            'from_area' => ['required', 'numeric', 'min:0'],
            'to_area' => ['required', 'numeric', 'min:0', 'gte:from_area'],

            // Real estate age
            'real_estate_age' => ['required', 'in:جديد,مستعمل'],
            'real_estate_age_number' => ['numeric', 'min:1', 'required_if:real_estate_age,مستعمل'],

            // Real estate property
            'real_estate_property' => ['required', 'in:دوبلكس,مودرن'],
            'description' => ['required', 'string'],

            // Optional fields with nullable values
            'bedrooms_number' => ['numeric', 'min:1'],
            'bathrooms_number' => ['numeric', 'min:1'],
            'reception_and_sitting_rooms_number' => ['numeric', 'min:1'],
            'street_width' => ['numeric', 'min:0'],
            'surrounding_streets_number' => ['numeric', 'min:0'],
            'real_estate_front' => ['in:شمال,جنوب,شرق,غرب,شمال شرق,شمال غرب,جنوب شرق,جنوب غرب'],


        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'نوع العقار مطلوب.',
            'type.in' => 'نوع العقار يجب أن يكون إيجار أو شراء.',

            'category_id.required' => 'فئة العقار مطلوبة.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',

            'city_id.required' => 'المدينة مطلوبة.',
            'city_id.exists' => 'المدينة المحددة غير موجودة.',

            'neighbourhood_id.required' => 'الحي مطلوب.',
            'neighbourhood_id.exists' => 'الحي المحدد غير موجود.',

            'location.required' => 'الموقع مطلوب.',
            'location.string' => 'يجب أن يكون الموقع نصاً.',


            'images.required' => 'صور الاعلان مطلوبة.',
            'images.array' => 'يجب أن تكون صور الاعلان في شكل مصفوفة.',
            'images.*.image' => 'كل ملف يجب أن يكون صورة.',
            'images.*.mimes' => 'يجب أن تكون صورة الاعلان بامتداد: jpeg, png, jpg, gif.',
            'images.*.max' => 'يجب ألا يتجاوز حجم صورة الاعلان 8 ميجابايت.',

            'from_area.required' => 'الحد الأدنى للمساحة مطلوب.',
            'from_area.numeric' => 'الحد الأدنى للمساحة يجب أن يكون رقماً.',
            'from_area.min' => 'الحد الأدنى للمساحة لا يمكن أن يكون سالباً.',

            'to_area.required' => 'الحد الأقصى للمساحة مطلوب.',
            'to_area.numeric' => 'الحد الأقصى للمساحة يجب أن يكون رقماً.',
            'to_area.min' => 'الحد الأقصى للمساحة لا يمكن أن يكون سالباً.',
            'to_area.gte' => 'الحد الأقصى للمساحة يجب أن يكون أكبر أو يساوي الحد الأدنى.',

            'real_estate_age.required' => 'عمر العقار مطلوب.',
            'real_estate_age.in' => 'عمر العقار يجب أن يكون جديد أو مستعمل.',

            'real_estate_age_number.required_if' => 'رقم عمر العقار مطلوب إذا كان العقار مستعملاً.',
            'real_estate_age_number.numeric' => 'رقم عمر العقار يجب أن يكون عدداً صحيحاً.',
            'real_estate_age_number.min' => 'رقم عمر العقار يجب أن يكون 1 على الأقل.',

            'real_estate_property.required' => 'صفة العقار مطلوبة.',
            'real_estate_property.in' => 'صفة العقار يجب أن تكون دوبلكس أو مودرن.',

            'description.required' => 'وصف العقار مطلوب.',
            'description.string' => 'يجب أن يكون الوصف نصاً.',

            'bedrooms_number.numeric' => 'عدد غرف النوم يجب أن يكون عدداً صحيحاً.',
            'bedrooms_number.min' => 'عدد غرف النوم يجب أن يكون 1 على الأقل.',

            'bathrooms_number.numeric' => 'عدد الحمامات يجب أن يكون عدداً صحيحاً.',
            'bathrooms_number.min' => 'عدد الحمامات يجب أن يكون 1 على الأقل.',

            'reception_and_sitting_rooms_number.numeric' => 'عدد غرف الاستقبال يجب أن يكون عدداً صحيحاً.',
            'reception_and_sitting_rooms_number.min' => 'عدد غرف الاستقبال يجب أن يكون 1 على الأقل.',

            'street_width.numeric' => 'عرض الشارع يجب أن يكون رقماً.',
            'street_width.min' => 'عرض الشارع لا يمكن أن يكون سالباً.',

            'surrounding_streets_number.numeric' => 'عدد الشوارع المحيطة يجب أن يكون عدداً صحيحاً.',
            'surrounding_streets_number.min' => 'عدد الشوارع المحيطة لا يمكن أن يكون سالباً.',

            'real_estate_front.in' => 'واجهة العقار يجب أن تكون إحدي الاتجاهات المتاحة.',
        ];
    }

}
