<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\Service;
use App\Models\ServiceUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatterBlastController extends Controller
{
    private int $PORT = 9001;


    public function validateValues(array $required_fields, array $values)
    {
        $arr = array_map(function ($field) use ($values) {
            if(!isset($values[$field]) || empty($values[$field])) {
                return false;
            }
            return true;
        }, $required_fields);

        for ($i = 0; $i < count($arr); ++$i) {
            if ($arr[$i] == false) {
                return false;
            }
        }
        return $values;
    }

    public function checkQuota(string $token)
    {
        $api_token = ApiToken::where('token', $token)->first();
        $workspace = $api_token->workspace;
        $total = $api_token->usages->map(function ($usage) {
            if($usage->service->id === 1) {
                return $usage->service->cost_per_ms * $usage->duration_in_ms;
            }
        });
        return $workspace->quota->limit < array_sum($total->toArray());
    }


    public function startConversation(Request $request)
    {
        $validated = $this->validateValues(['prompt'], [
            'prompt' => $request->input('prompt') ?? null,
        ]);

        if($validated === false) {
            return response()->json([
                'type' =>  '/api/chat/conversation',
                'title' => 'Bad Request',
                'status' => 400,
                'detail' => 'The request is malformed',
            ], 400);
        }

        $token = $request->header('X-API-TOKEN') ?? null;
        if ($this->checkQuota($token)) {
            return response()->json([
                'type' =>  '/api/chat/conversation',
                'title' => 'Forbidden',
                'status' => 403,
                'detail' => 'The user is not allowed to make the request',
            ], 403);
        }

        $url = config('app.ai_service') . $this->PORT;

        $conversation_id = rand(1000000, 10000000);
        $conversation = Http::post($url . '/conversation', [
            'conversationId' => $conversation_id,
        ]);

        if($conversation->status() === 201) {
            $request_ai = Http::withHeader('content-type', 'text/plain')->post($url . '/conversation/' . $conversation_id, $validated);
            return response()->json([
                'conversation_id' =>  $conversation_id,
                'response' => $request_ai->json(),
                'is_final' => false,
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function getConversation(Request $request, int $conversation_id)
    {
        $token = $request->header('X-API-TOKEN') ?? null;
        if ($this->checkQuota($token)) {
            return response()->json([
                'type' =>  '/api/chat/conversation',
                'title' => 'Forbidden',
                'status' => 403,
                'detail' => 'The user is not allowed to make the request',
            ], 403);
        }

        $url = config('app.ai_service') . $this->PORT;

        $request_ai = Http::withHeader('content-type', 'text/plain')->get($url . '/conversation/' . $conversation_id);
        if($request_ai->status() === 200) {
            $value = $request_ai->body();
            $arr = explode(' ', $value);
            $ms = explode(' ', $value)[count($arr) -  1];
            $ms = str_replace('ms', '', $ms);
            $api_token = ApiToken::where('token', $token)->first();
            ServiceUsage::create([
                'duration_in_ms' => $ms,
                'api_token_id' => $api_token->id,
                'service_id' => Service::find(1)->id,
                'usage_started_at' => now(),
            ]);
            return response()->json([
                'conversation_id' =>  $conversation_id,
                'response' => $request_ai->body(),
                'is_final' => true,
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function continueConversation(Request $request, int $conversation_id)
    {
        $validated = $this->validateValues(['prompt'], [
            'prompt' => $request->input('prompt') ?? null,
        ]);

        if($validated === false) {
            return response()->json([
                'type' =>  '/api/chat/conversation',
                'title' => 'Bad Request',
                'status' => 400,
                'detail' => 'The request is malformed',
            ], 400);
        }

        $token = $request->header('X-API-TOKEN') ?? null;
        if ($this->checkQuota($token)) {
            return response()->json([
                'type' =>  '/api/chat/conversation',
                'title' => 'Forbidden',
                'status' => 403,
                'detail' => 'The user is not allowed to make the request',
            ], 403);
        }

        $url = config('app.ai_service') . $this->PORT;

        $request_ai = Http::withHeader('content-type', 'text/plain')->post($url . '/conversation/' . $conversation_id, $validated);
        if($request_ai->status() === 200) {
            return response()->json([
                'conversation_id' =>  $conversation_id,
                'response' => $request_ai->json(),
                'is_final' => false,
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }
}
