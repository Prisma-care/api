<?php

namespace App\Http\Controllers;

use Validator;
use App\Album;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    private $keyTranslations = array(
        'id' => 'id',
        'title' => 'title',
        'description' => 'description'
    );

    /**
     * Display a listing of the resource.
     *
     * @param  int $patiendId
     * @return \Illuminate\Http\Response
     */
    public function index($patientId)
    {
        $albums = Patient::findOrFail($patientId)->albums;
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
                    'favorited' => $story->favorited,
                    'source' => $story->asset_name
                ];
            }
            $allAlbums[] = $thisAlbum;
        }

        return response()->success($allAlbums, 200, 'OK');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $patientId)
    {
        Patient::findOrFail($patientId);

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:albums'
        ]);
        if ($validator->fails()) {
            return response()->exception($validator->errors(), 400);
        }

        $album = new Album([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'patient_id' => $patientId
        ]);
        if (!$album->save()) {
            return response()->exception('The album could not be created', 500);
        }

        $createdAlbum = [
            'id' => $album->id,
            'title' => $album->title
        ];
        $location = $request->url() . '/' . $album->id;
        return response()->success($createdAlbum, 201, 'Created', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show($patientId, $albumId)
    {
        Patient::findOrFail($patientId);
        $album = Album::findOrFail($albumId);
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
                'favorited' => $story->favorited,
                'source' => $story->asset_name
            ];
        }

        return response()->success($thisAlbum, 200, 'OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $patientId, $albumId)
    {
        if (!$request->isMethod('PATCH')) {
            return response()->exception('Method not allowed', 405);
        }

        $album = Album::findOrFail($albumId);
        $values = $request->all();
        foreach (array_keys($values) as $key) {
            $translatedKey = (isset($this->keyTranslations[$key]))
                                ? $this->keyTranslations[$key]
                                : null;
            if ($translatedKey) {
                $album[$translatedKey] = $values[$key];
            }
        }
        if (!$album->update()) {
            return response()->exception('The album could not be updated', 500);
        }

        return response()->success([], 200, 'OK');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy($patienId, $albumId)
    {
        if (Album::destroy($albumId)) {
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception('The album could not be deleted', 500);
        }
    }
}
