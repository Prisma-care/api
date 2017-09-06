<?php

namespace App\Http\Controllers\Heritage;

use App\Album;
use App\Heritage;
use Illuminate\Http\Request;
use App\Http\Requests\DefaultAlbum as DefaultAlbumRequest;
use App\Http\Controllers\Controller;

class DefaultAlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }


    /**
     * @param DefaultAlbumRequest\Index $request
     * @return mixed
     */
    public function index(DefaultAlbumRequest\Index $request)
    {
        $albums = Album::with('heritage')->get()->where('patient_id', '=', null)->values()->all();
        return response()->success($albums, 200, 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\DefaultAlbum\Show $request
     * @param  int  $albumId
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultAlbumRequest\Show $request, $albumId)
    {
        $album = Album::with('heritage')->get()
                                        ->where('patient_id', '=', null)
                                        ->where('id', '=', $albumId)
                                        ->first();
        return response()->success($album, 200, 'OK');
    }


    /**
     * @param DefaultAlbumRequest\Store $request
     * @return mixed
     */
    public function store(DefaultAlbumRequest\Store $request)
    {
        $album = new Album([
            'title' => $request->input('title')
        ]);
        if (!$album->save()) {
            return response()->exception('The default album could not be created', 500);
        }

        $location = $request->url() . '/' . $album->id;
        return response()->success($album, 201, 'Created', $location);
    }


    /**
     * @param DefaultAlbumRequest\Update $request
     * @param $albumId
     * @return mixed
     */
    public function update(DefaultAlbumRequest\Update $request, $albumId)
    {
        $album = Album::findOrFail($albumId);
        if (!$album->isDefault()) {
            return response()->exception("The album you're trying to update is not a default album", 400);
        }
        $album->title = $request->input('title') ?: $album->title;
        if (!$album->update()) {
            return response()->exception("The album could not be updated", 500);
        }

        return response()->success([], 200, 'OK');
    }


    /**
     * @param $albumId
     * @return mixed
     */
    public function destroy($albumId)
    {
        $album = Album::findOrFail($albumId);
        if (!$album->isDefault()) {
            return response()->exception("The album you're trying to update is not a default album", 400);
        }

        if ($album->delete()) {
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception('The album could not be deleted', 500);
        }
    }
}
