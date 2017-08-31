<?php

namespace App\Http\Controllers;

use App\Album;
use App\Heritage;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Heritage as HeritageRequest;

class HeritageController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Heritage\Index  $request
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function index(HeritageRequest\Index $request, $albumId)
    {
        $album = Album::findOrFail($albumId);
        $heritage = $album->heritage;
        return response()->success($heritage, 200, 'OK');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Heritage\Store  $request
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function store(HeritageRequest\Store $request, $albumId)
    {
        $album = Album::findOrFail($albumId);
        if (!$album->isDefault()) {
            return response()->exception('You can only store new heritage in a default album', 400);
        }
        $heritage = new Heritage([
            'description' => $request->input('description'),
            'happened_at' => $request->input('happenedAt'),
            'album_id' => $albumId
        ]);
        if (!$heritage->save()) {
            return response()->exception('The heritage could not be created', 500);
        }

        $location = $request->url() . '/' . $heritage->id;
        return response()->success($heritage, 201, 'Created', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\Heritage\Show $request
     * @param  int  $heritageId
     * @return \Illuminate\Http\Response
     */
    public function show(HeritageRequest\Show $reqeust, $albumId, $heritageId)
    {
        $heritage = Heritage::findOrFail($heritageId);
        $gotHeritage = [
            'id' => $heritage->id,
            'filename' => $heritage->filename,
            'title' => $heritage->title,
            'description' => $heritage->description,
            'happened_in' => $heritage->happened_in,
        ];

        return response()->success($gotHeritage, 200, 'OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Heritage\Update $request
     * @param  int  $heritageId
     * @return \Illuminate\Http\Response
     */
    public function update(HeritageRequest\Update $request, $albumId, $heritageId)
    {
        $heritage = Heritage::findOrFail($heritageId);
        $heritage->description = $request->input('description') ?: $heritage->description;
        $heritage->happened_at = $request->input('happened_at') ?: $heritage->happenedAt;
        if (!$heritage->update()) {
            return response()->exception("The story could not be updated", 500);
        }

        return response()->success([], 200, 'OK');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Heritage\Destroy $request
     * @param  int  $heritageId
     * @return \Illuminate\Http\Response
     */
    public function destroy(HeritageRequest\Destroy $request, $albumId, $heritageId)
    {
        $heritage = Heritage::findOrFail($heritageId);
        if ($heritage->delete()) {
            $directory = storage_path("app/heritage/$heritageId");
            File::deleteDirectory($directory);
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception("The heritage could not be deleted", 500);
        }
    }
}
