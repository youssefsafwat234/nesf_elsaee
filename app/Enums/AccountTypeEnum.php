<?php

namespace App\Enums;

enum AccountTypeEnum: string
{
    case ENDUSER_ACCOUNT = 'حساب مستخدم';
    case FREELANCER_ACCOUNT = 'مسوق عقاري';
    case    OFFICE_ACCOUNT = 'مكتب عقاري' ;
    case COMPANY_ACCOUNT = 'شركة عقارية';
    case Service_Provider_Account = 'مقدم خدمة';
}
