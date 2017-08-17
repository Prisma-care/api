<?php

namespace App\Http\Controllers;

use App\Heritage;

class HeritageController extends Controller
{

    /**
     * @param Heritage $heritage
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Heritage $heritage)
    {
        $heritage = Heritage::firstOrFail($heritage);
        $responseCode = 200;
        $gotHeritage = [
            'id' => $heritage->id,
            'filename' => $heritage->filename,
            'title' => $heritage->title,
            'description' => $heritage->description,
            'happened_in' => $heritage->happened_in,
        ];

        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'OK'
            ],
            'response' => $gotHeritage
        ];

        return response()->json($response, $responseCode);
    }
}
