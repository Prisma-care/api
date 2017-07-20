<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;

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
        $album = new Album;
        $album->title = $request->input('title');
        $album->description = $request->input('description');
        $album->profiles_id = $patientId;

        $album->save();

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
