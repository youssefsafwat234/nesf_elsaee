<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CustomValidationException extends ValidationException
{
    public function render($request): JsonResponse
    {
        return new JsonResponse([
            'message' => 'يوجد اخطاء فى البيانات برجاء مراجعة البيانات',
            'success' => false,
            'errors' => $this->validator->errors()->getMessages(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
