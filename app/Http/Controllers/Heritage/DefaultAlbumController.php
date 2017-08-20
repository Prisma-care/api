<?php

namespace App\Http\Controllers;

use App\Album;
use App\Heritage;
use Illuminate\Http\Request;
use App\Http\Requests\DefaultAlbum as DefaultAlbumRequest;
use App\Http\Controllers\Controller;

class DefaultAlbumController extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DefaultAlbumRequest\Store $request)
    {
        $album = new Album([
            'title' => $request->input('title'),
            // TODO this should be nullable
            'patient_id' => 1,
            'is_default' => true
        ]);
        if (!$album->save()) {
            return response()->exception('The default album could not be created', 500);
        }

        $location = $request->url() . '/' . $album->id;
        return response()->success($album, 201, 'Created', $location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DefaultAlbumRequest\Update $request, $albumId)
    {
        $album = Album::findOrFail($albumId);
        if (!$album->is_default) {
            return response()->exception("The album you're trying to update is not a default album", 400);
        }

        $album->description = $request->input('title') ?: $album->title;
        if (!$album->update()) {
            return response()->exception("The album could not be updated", 500);
        }

        return response()->success([], 200, 'OK');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($albumId)
    {
        $album = Album::findOrFail($albumId);
        if (!$album->is_default) {
            return response()->exception("The album you're trying to update is not a default album", 400);
        }

        if ($album->delete()) {
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception('The album could not be deleted', 500);
        }
    }
}
