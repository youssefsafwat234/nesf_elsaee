<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SocialRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // for all users
            'provider' => 'required',
            'token' => 'required',
            'accountType' => ['required', 'in:شركة عقارية,مسوق عقاري,مكتب عقاري,حساب مستخدم'],
            'phone' => ['required', 'string', 'unique:users,phone'],

            // For non-end users
            'subscriptionType' => ['nullable', 'in:شهري,سنوي', 'required_unless:accountType,حساب مستخدم'],
            'whatsapp_phone' => ['nullable', 'string', 'unique:users,whatsapp_phone', 'required_unless:accountType,حساب مستخدم',],
            'logo' => ['nullable', 'image', 'required_unless:accountType,مسوق عقاري,حساب مستخدم'], // logo can be nullable for freelancer],
            'city' => ['nullable', 'string', 'required_unless:accountType,حساب مستخدم',],
            'location' => ['nullable', 'string', 'required_unless:accountType,حساب مستخدم',],
            'val_certification' => ['nullable', 'file', 'required_unless:accountType,حساب مستخدم',],
            'other_certifications' => ['nullable', 'file', 'required_unless:accountType,حساب مستخدم',],
            'website_url' => ['nullable', 'url', 'required_unless:accountType,حساب مستخدم',],

            // For company and office users only
            'commercial_register' => ['nullable', 'file', 'required_if:accountType,شركة عقارية,مكتب عقاري',],
            'manager_name' => ['nullable', 'string', 'required_if:accountType,شركة عقارية,مكتب عقاري',],
            'social_media_url' => ['nullable', 'url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'twitter_url' => ['nullable', 'url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'instagram_url' => ['nullable', 'url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'snapchat_url' => ['nullable', 'url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'branches' => ['nullable', 'string', 'required_if:accountType,شركة عقارية,مكتب عقاري',],

            // For freelancers only
            'neighborhood' => ['string', 'required_if:accountType,مسوق عقاري,مقدم خدمة'],

            // for only service account
            'service_type' => ['string', 'required_if:accountType,مقدم خدمة', 'in:صاحب عقار,مقاول'],


        ];
    }

    public function messages(): array
    {
        return [
            // For all users
            'provider.required' => 'مزود الخدمة مطلوب.',
            'token.required' => 'الرمز مطلوب.',
            'accountType.required' => 'نوع الحساب مطلوب.',
            'accountType.in' => 'نوع الحساب غير صالح.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            // For non-end users
            'subscriptionType.in' => 'نوع الاشتراك غير صالح، يجب أن يكون شهري أو سنوي.',
            'subscriptionType.required_unless' => 'نوع الاشتراك مطلوب إلا إذا كان الحساب حساب مستخدم.',

            'whatsapp_phone.string' => 'يجب أن يكون رقم واتساب نصًا.',
            'whatsapp_phone.unique' => 'رقم واتساب مستخدم بالفعل.',
            'whatsapp_phone.required_unless' => 'رقم واتساب مطلوب إلا إذا كان الحساب حساب مستخدم.',

            'logo.image' => 'يجب أن يكون الشعار صورة.',
            'logo.required_unless' => 'الشعار مطلوب إلا إذا كان الحساب مسوق عقاري أو حساب مستخدم.',

            'city.string' => 'يجب أن تكون المدينة نصًا.',
            'city.required_unless' => 'المدينة مطلوبة إلا إذا كان الحساب حساب مستخدم.',

            'location.string' => 'يجب أن يكون الموقع نصًا.',
            'location.required_unless' => 'الموقع مطلوب إلا إذا كان الحساب حساب مستخدم.',

            'val_certification.file' => 'يجب أن تكون شهادة التقييم ملفًا.',
            'val_certification.required_unless' => 'شهادة التقييم مطلوبة إلا إذا كان الحساب حساب مستخدم.',

            'other_certifications.file' => 'يجب أن تكون الشهادات الأخرى ملفًا.',
            'other_certifications.required_unless' => 'الشهادات الأخرى مطلوبة إلا إذا كان الحساب حساب مستخدم.',

            'website_url.url' => 'يجب أن يكون رابط الموقع صالحًا.',
            'website_url.required_unless' => 'رابط الموقع مطلوب إلا إذا كان الحساب حساب مستخدم.',

            // For company and office users only
            'commercial_register.file' => 'يجب أن يكون السجل التجاري ملفًا.',
            'commercial_register.required_if' => 'السجل التجاري مطلوب إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            'manager_name.string' => 'يجب أن يكون اسم المدير نصًا.',
            'manager_name.required_if' => 'اسم المدير مطلوب إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            'social_media_url.url' => 'يجب أن يكون رابط السوشيال ميديا صالحًا.',
            'social_media_url.required_if' => 'رابط السوشيال ميديا مطلوب إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            'twitter_url.url' => 'يجب أن يكون رابط تويتر صالحًا.',
            'twitter_url.required_if' => 'رابط تويتر مطلوب إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            'instagram_url.url' => 'يجب أن يكون رابط إنستغرام صالحًا.',
            'instagram_url.required_if' => 'رابط إنستغرام مطلوب إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            'snapchat_url.url' => 'يجب أن يكون رابط سناب شات صالحًا.',
            'snapchat_url.required_if' => 'رابط سناب شات مطلوب إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            'branches.string' => 'يجب أن تكون الفروع نصًا.',
            'branches.required_if' => 'الفروع مطلوبة إذا كان الحساب شركة عقارية أو مكتب عقاري.',

            // For freelancers only
            'neighborhood.string' => 'يجب أن يكون الحي نصًا.',
            'neighborhood.required_if' => 'الحي مطلوب إذا كان الحساب مسوق عقاري.',

            // for only services accounts
            'service_type.string' => 'نوع الخدمة يجب أن يكون نصاً.',
            'service_type.required_if' => 'نوع الخدمة مطلوب إذا كان نوع الحساب مقدم خدمة.',
            'service_type.in' => 'نوع الخدمة يجب أن يكون إما صاحب عقار أو مقاول.',

        ];
    }

}
