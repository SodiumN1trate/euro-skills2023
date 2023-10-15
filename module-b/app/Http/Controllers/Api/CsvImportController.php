<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Token;
use App\Models\Usage;
use Illuminate\Http\Request;

class CsvImportController extends Controller
{
    public function import(Request $request) {
        $file = explode("\n", file_get_contents($request->file('file')));
        foreach ($file as $key => $row) {
            if($key === 0 || count($file) > $key) {
                continue;
            }
            $row = str_getcsv($row);
            $token = Token::where('name', $row[2])->first();
            $service = Service::where('name', $row[5])->first();
            $usage = Usage::create([
                'duration' => $row[3],
                'started_at' => $row[4],
                'token_id' => $token->id,
                'service_id' => $service->id,
            ]);
        }

        return response()->json([
            'success',
        ]);
    }
}
