<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MindReaderController extends Controller
{

    private int $PORT = 9003;

    public function recognize(Request $request)
    {
        if($request->file('image') === null) {
            return response()->json([
                'type' =>  '/api/chat/conversation',
                'title' => 'Bad Request',
                'status' => 400,
                'detail' => 'The request is malformed',
            ], 400);
        }


        $url = config('app.ai_service') . $this->PORT;

        $read = Http::withHeader('content-type', 'multipart/form-data')->post($url . '/recognize', [
            'image' => $request->file('image'),
        ]);

        return response()->json([]);
//        if($generate->status() === 201) {
//            return response()->json([
//                'job_id' => $generate->json()['job_id'],
//            ]);
//        }

    }
}
