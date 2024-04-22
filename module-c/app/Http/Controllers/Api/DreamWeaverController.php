<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DreamWeaverController extends Controller
{
    private int $PORT = 9002;


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
            if($usage->service->id === 2) {
                return $usage->service->cost_per_ms * $usage->duration_in_ms;
            }
        });
        return $workspace->quota->limit < array_sum($total->toArray());
    }

    public function generate(Request $request)
    {
        $validated = $this->validateValues(['text_prompt'], [
            'text_prompt' => $request->input('text_prompt') ?? null,
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

        $generate = Http::withHeader('content-type', 'application/json')->post($url . '/generate', $validated);

        if($generate->status() === 201) {
            return response()->json([
                'job_id' => $generate->json()['job_id'],
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function status(Request $request, string $job_id)
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

        $generate = Http::withHeader('content-type', 'application/json')->get($url . "/status/$job_id");

        if($generate->status() === 200) {
            return response()->json([
                "status" => $generate->json()['status'],
                "progress" => $generate->json()['progress'],
                "image_url" => $generate->json()['image_url'],
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function result(Request $request, string $job_id)
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

        $generate = Http::withHeader('content-type', 'application/json')->get($url . "/result/$job_id");

        if($generate->status() === 200) {
            return response()->json([
                "resource_id" => $generate->json()['resource_id'],
                "image_url" => $generate->json()['image_url'],
                "finished_at" => now(),
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function upscale(Request $request)
    {
        $validated = $this->validateValues(['resource_id'], [
            'resource_id' => $request->input('resource_id') ?? null,
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

        $generate = Http::withHeader('content-type', 'application/json')->post($url . '/upscale', $validated);
        if($generate->status() === 201) {
            return response()->json([
                'job_id' => $generate->json()['job_id'],
                "started_at" => $generate->json()['started_at'],
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function zoomin(Request $request)
    {
        $validated = $this->validateValues(['resource_id'], [
            'resource_id' => $request->input('resource_id') ?? null,
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

        $generate = Http::withHeader('content-type', 'application/json')->post($url . '/zoom/in', $validated);
        if($generate->status() === 201) {
            return response()->json([
                'job_id' => $generate->json()['job_id'],
                'started_at' => $generate->json()['started_at'],
            ]);
        }

        return response()->json([
            'type' =>  '/api/chat/conversation',
            'title' => 'Service Unavailable',
            'status' => 503,
            'detail' => 'The service is not available',
        ], 503);
    }

    public function zoomout(Request $request)
    {
        $validated = $this->validateValues(['resource_id'], [
            'resource_id' => $request->input('resource_id') ?? null,
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

        $generate = Http::withHeader('content-type', 'application/json')->post($url . '/zoom/out', $validated);
        if($generate->status() === 201) {
            return response()->json([
                'job_id' => $generate->json()['job_id'],
                'started_at' => $generate->json()['started_at'],
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
