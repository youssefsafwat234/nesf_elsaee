<?php

namespace App\Http\Controllers\Api;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Opinion;
use App\Models\User;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function sendOpinion(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'sender_id' => 'required|exists:users,id',
                'advertisement_id' => 'required|exists:advertisements,id',
            ], [
                'receiver_id.required' => 'من فضلك ارسل المستخدم',
                'receiver_id.exists' => 'المستخدم غير موجود',
                'sender_id.required' => 'من فضلك ارسل الشركة او المكتب',
                'sender_id.exists' => 'الشركة او المكتب غير موجود',
                'advertisement_id.required' => 'من فضلك ارسل الإعلان',
                'advertisement_id.exists' => 'الإعلان غير موجود',
            ]
            );

            $senderType = User::findOrFail($request->sender_id)->accountType;

            if ($senderType == AccountTypeEnum::COMPANY_ACCOUNT->value || $senderType == AccountTypeEnum::OFFICE_ACCOUNT->value) {

                $opinion = Opinion::where('sender_id', $request->sender_id)->where('receiver_id', $request->receiver_id)->where('advertisement_id', $request->advertisement_id)->first();


                if ($opinion->status == 'not_answered_yet') {
                    return response()->json([
                        'error' => ' لقد قمت بإرسال الاستطلاع مسبقا لهذا المستخدم انتظر لحين رد المستخدم',
                    ], 409);
                }
                $validatedData['status'] = 'not_answered_yet';

                $opinion = \App\Models\Opinion::create($validatedData);

                return response()->json([
                    'data' => $opinion,
                ], 201);


            } else {
                return response()->json([
                    'error' => 'لا يمكنك ارسال استطلاع الا اذا كنت مكتب عقارى أو شركة عقارية',
                ], 403);
            }
        }

        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);

        }


    }

    function sendOpinionResponse(Request $request)
    {

        $validatedData = $request->validate([
                'opinion_id' => 'required|exists:opinions,id',
                'view_status' => 'required|boolean',
                'satisfy_status' => 'required|boolean',
                'content' => 'required',
            ]
            ,
            [
                'opinion_id.required' => 'من فضلك ارسل رقم الاستطلاع',
                'opinion_id.exists' => 'الاستطلاع غير موجود',
                'view_status.required' => 'من فضلك ارسل إجابة السؤال (هل رأيت العقار)',
                'view_status.boolean' => 'حالة السؤال يجب ان تكون نعم ام لا ',
                'satisfy_status.required' => 'من فضلك ارسل إجابة السؤال (هل انت راضى عن العقار)',
                'satisfy_status.boolean' => 'حالة السؤال يجب ان تكون نعم ام لا ',
            ]);

        $request->merge(['status' => 'answered']);
        Opinion::findOrFail($request->opinion_id)->update($request->only('view_status', 'satisfy_status', 'content', 'status'));

        return response()->json([
            'data' => 'تم الإجابة على الاستطلاع بنجاح',
        ], 200);
    }

    function getUserOpinions()
    {
        $opinions = Opinion::where('receiver_id', auth()->id())->get();
        return response()->json([
            'data' => $opinions,
        ], 200);

    }
}
