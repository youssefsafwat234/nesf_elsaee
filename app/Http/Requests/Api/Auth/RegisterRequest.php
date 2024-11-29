<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;


class RegisterRequest extends FormRequest
{

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
            // For all users
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/', 'confirmed'],
            'accountType' => ['required', 'in:شركة عقارية,مسوق عقاري,مكتب عقاري,حساب مستخدم,مقدم خدمة'],
            'phone' => ['required', 'string', 'unique:users,phone'],


            // For non-end users
            'subscriptionType' => ['in:شهري,سنوي', 'required_unless:accountType,مقدم خدمة,حساب مستخدم'],
            'whatsapp_phone' => ['string', 'unique:users,whatsapp_phone', 'required_unless:accountType,حساب مستخدم'],
            'logo' => ['image', 'required_unless:accountType,حساب مستخدم'], // logo can be nullable for freelancer],
            'city' => ['string', 'required_unless:accountType,حساب مستخدم',],
            'location' => ['string', 'required_unless:accountType,حساب مستخدم',],
            // not for also service account
            'val_certification' => ['file', 'required_unless:accountType,حساب مستخدم,مقدم خدمة',],
            'other_certifications' => ['file', 'required_unless:accountType,حساب مستخدم,مقدم خدمة',],

            'website_url' => ['url', 'required_unless:accountType,حساب مستخدم',],


            // For company and office users only
            'commercial_register' => ['file', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'manager_name' => ['string', 'required_if:accountType,شركة عقارية,مكتب عقاري',],
            'social_media_url' => ['url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'twitter_url' => ['url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'instagram_url' => ['url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'snapchat_url' => ['url', 'required_if:accountType,شركة عقارية,مكتب عقاري'],
            'branches' => ['string', 'required_if:accountType,شركة عقارية,مكتب عقاري',],

            // For freelancers only
            'neighborhood' => ['string', 'required_if:accountType,مسوق عقاري,مقدم خدمة'],

            // for only service account
            'service_type' => ['required_if:accountType,مقدم خدمة', 'string', 'in:صاحب عقار,مقاول,محامي,مكتب هندسي'],
        ];
    }

    public function messages(): array
    {
        return [
            // For all users
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفًا.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'يجب أن يكون البريد الإلكتروني نصًا.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا.',
            'email.max' => 'يجب ألا يتجاوز البريد الإلكتروني 255 حرفًا.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف كبير، حرف صغير، رقم، ورمز خاص واحد على الأقل.',
            'password.confirmed' => 'كلمة المرور غير متطابقة',

            'accountType.required' => 'نوع الحساب مطلوب.',
            'accountType.in' => 'نوع الحساب غير صالح.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            // For non-end users
            'subscriptionType.required_unless' => 'نوع الاشتراك مطلوب إلا إذا كان الحساب حساب مستخدم.',
            'subscriptionType.in' => 'نوع الاشتراك غير صالح، يجب أن يكون شهري أو سنوي.',

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
            'neighborhood.required_if' => 'الحي مطلوب إذا كان الحساب مسوق عقاري او مقدم خدمة.',

            // for only services accounts
            'service_type.string' => 'نوع الخدمة يجب أن يكون نصاً.',
            'service_type.required_if' => 'نوع الخدمة مطلوب إذا كان نوع الحساب مقدم خدمة.',
            'service_type.in' => 'نوع الخدمة يجب أن يكون إما صاحب عقار أو مقاول.',


        ];
    }


}

