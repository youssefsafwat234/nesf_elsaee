<?php

namespace App\Enums;

enum AccountTypeEnum: string
{
    case ENDUSER_ACCOUNT = 'حساب مستخدم';
    case FREELANCER_ACCOUNT = 'مسوق عقاري';
    case    OFFICE_ACCOUNT = 'مكتب عقاري';
    case COMPANY_ACCOUNT = 'شركة عقارية';
    case Service_Provider_Account = 'مقدم خدمة';

    static  function getAccountValues()
    {
        return [
            AccountTypeEnum::ENDUSER_ACCOUNT->value,
            AccountTypeEnum::COMPANY_ACCOUNT->value,
            AccountTypeEnum::OFFICE_ACCOUNT->value,
            AccountTypeEnum::FREELANCER_ACCOUNT->value,
            AccountTypeEnum::Service_Provider_Account->value,
        ];
    }
}
