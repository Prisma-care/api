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
        try {
            Patient::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->exception("There is no $failingResource resource with the provided id.", 400);
        }

        $albums = Patient::find($patientId)->albums;
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

        return response()->success($allAlbums, 200, 'OK');
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
            Patient::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->exception("There is no $failingResource resource with the provided id.", 400);
        }

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
        try {
            Patient::findOrFail($patientId);
            Album::findOrFail($albumId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->exception("There is no $failingResource resource with the provided id.", 400);
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

        return response()->success($thisAlbum, 200, 'OK');
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
    public function update(Request $request, $patientId, $albumId)
    {
        if (!$request->isMethod('PATCH')) {
            return response()->exception('Method not allowed', 405);
        }

        try {
            Patient::findOrFail($patientId);
            Album::findOrFail($albumId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->exception("There is no $failingResource resource with the provided id.", 400);
        }

        $album = Album::find($albumId);
        $values = array_filter($request->all());
        foreach (array_keys($values) as $key) {
            $translatedKey = (isset($this->keyTranslations[$key]))
                                ? $this->keyTranslations[$key]
                                : null;
            if ($translatedKey) {
                $story[$translatedKey] = $values[$key];
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
