<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Heritage;

class HeritageController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(heritage $heritage)
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
