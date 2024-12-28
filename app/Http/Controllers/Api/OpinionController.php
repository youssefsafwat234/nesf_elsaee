<?php

namespace App\Http\Controllers\Api;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Opinion\SendOpinionRequest;
use App\Http\Requests\Opinion\SendUserOpinionResponseRequest;
use App\Models\Opinion;
use App\Models\User;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function sendOpinion(SendOpinionRequest $request)
    {
        try {
            $senderType = auth()?->user()?->accountType;

            if ($senderType == AccountTypeEnum::COMPANY_ACCOUNT->value || $senderType == AccountTypeEnum::OFFICE_ACCOUNT->value) {

                $opinion = Opinion::where('sender_id', auth()?->id())->where('receiver_id', $request->receiver_id)->where('advertisement_id', $request->advertisement_id)->first();


                if ($opinion && $opinion->status == 'not_answered_yet') {
                    return response()->json([
                        'success' => false,
                        'message' => ' لقد قمت بإرسال الاستطلاع مسبقا لهذا المستخدم انتظر لحين رد المستخدم',
                    ], 409);
                }
                $validatedData['status'] = 'not_answered_yet';

                $opinion = \App\Models\Opinion::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $request->receiver_id,
                    'advertisement_id' => $request->advertisement_id,
                    'status' => 'not_answered_yet',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم ارسال الاستطلاع بنجاح',
                    'data' => $opinion,
                ], 201);


            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكنك ارسال استطلاع الا اذا كنت مكتب عقارى أو شركة عقارية',
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);

        }
    }


    function sendOpinionResponse(SendUserOpinionResponseRequest $request)
    {
        $opinion = Opinion::findOrFail($request->opinion_id);
        if ($opinion->receiver_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك الرد على الاستطلاع الا اذا كنت المستقبل',
            ], 403);
        }
        if ($opinion->status == 'answered') {
            return response()->json([
                'success' => false,
                'message' => 'لقد تم الرد على الاستطلاع مسبقا',
            ], 409);
        }

        $request->merge(['status' => 'answered']);
        Opinion::findOrFail($request->opinion_id)->update($request->only('view_status', 'satisfy_status', 'content', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'تم الإجابة على الاستطلاع بنجاح',
        ], 200);
    }

    function getUserOpinions()
    {
        $opinions = Opinion::where('receiver_id', auth()->id())->get();
        return response()->json([
            'success' => true,
            'data' => $opinions,
        ], 200);

    }


    function getUserOpinionsForCompanyOrOffice()
    {
        $opinions = Opinion::where('sender_id', auth()->id())->without('sender')->get();
        return response()->json([
            'success' => true,
            'data' => $opinions,
        ], 200);

    }


    function getAllEndUsers()
    {
        $users = User::select(['id', 'name', 'email'])->where('accountType', AccountTypeEnum::ENDUSER_ACCOUNT->value)->get();
        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);

    }
}
