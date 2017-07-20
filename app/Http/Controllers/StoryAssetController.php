<?php

namespace App\Http\Controllers;

use App\Story;
use Illuminate\Http\Request;

class StoryAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $patientId, $storyId)
    {
        $story = Story::find($storyId);

        if (!$request->hasFile('asset')) {
            return response()->json([
                 'code' => 400,
                 'message' => 'No asset was provided or the form-data request was malformed'
            ]);
        } elseif (!$request->file('asset')->isValid()) {
            return response()->json([
                 'code' => 500,
                 'message' => 'Asset upload failed, please try again later.'
            ]);
        }

        //$this->retrieveItem('headers', $key, $default);

        $PUBLIC_DIR = '/public';
        $UPLOADS_FOLDER = '/img/storyUploads/';

        $assetName = $story->id . '.' . $request->asset->extension();
        $location = base_path() . $PUBLIC_DIR . $UPLOADS_FOLDER;
        $request->file('asset')->move($location, $assetName);

        $story->file_name = $UPLOADS_FOLDER . $assetName;

        $responseCode = 201;
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => env('APP_URL') . $story->file_name
            ],
            'response' => [
                'id' => $story->id
            ]
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
