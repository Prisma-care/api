<?php

namespace App\Http\Controllers;

use Validator;
use App\Album;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Album::all();
        $allAlbums = [];
        foreach ($albums as $album) {
            $thisAlbum = [
               'id' => $album->id,
               'title' => $album->title,
               'stories' => []
            ];
            $stories = Album::find($album->id)->stories;
            foreach ($stories as $story) {
                $thisAlbum['stories'][] = [
                    'id' => $story->id,
                    'description' => $story->description,
                    'type' => '',
                    'source' => $story->file_name
                ];
            }
            $allAlbums[] = $thisAlbum;
        }

        $responseCode = 200;
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'OK'
            ],
            'response' => $allAlbums
        ];
        return response()->json($response, $responseCode);
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
    public function store(Request $request, $patientId)
    {
        try {
            Profile::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->json([
                'code' => 400,
                'message' => "There is no $failingResource resource with the provided id."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:albums'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()
            ]);
        }

        $album = new Album([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'profiles_id' => $patientId
        ]);
        if (!$album->save()) {
            return response()->json([
                'code' => 500,
                'message' => 'The album could not be created'
            ]);
        }

        $responseCode = 201;
        $createdAlbum = [
            'id' => $album->id,
            'title' => $album->title
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => $request->url() . '/' . $album->id
            ],
            'response' => $createdAlbum
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show($patientId, $albumId)
    {
        try {
            Profile::findOrFail($patientId);
            Album::findOrFail($albumId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->json([
                'code' => 400,
                'message' => "There is no $failingResource resource with the provided id."
            ]);
        }

        $album = Album::find($albumId);
        $thisAlbum = [
           'id' => $album->id,
           'title' => $album->title,
           'stories' => []
        ];
        $stories = Album::find($album->id)->stories;
        foreach ($stories as $story) {
            $thisAlbum['stories'][] = [
                'id' => $story->id,
                'description' => $story->description,
                'type' => '',
                'source' => $story->file_name
            ];
        }

        $responseCode = 200;
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'OK'
            ],
            'response' => $thisAlbum
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $album = Album::find($album);
        $album->title = $request->title;
        $album->description = $request->description;
        $album->profiles_id = $request->profiles_id;

        $album->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        Album::destroy($album);
    }
}
