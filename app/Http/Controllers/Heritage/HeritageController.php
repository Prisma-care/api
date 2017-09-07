<?php

namespace App\Http\Controllers\Heritage;

use App\Album;
use App\Heritage;
use App\Http\Controllers\Controller;
use File;
use App\Http\Requests\Heritage as HeritageRequest;

/**
 * Class HeritageController
 * @package App\Http\Controllers\Heritage
 * @resource Heritage\Heritage
 *
 * A Heritage is a Story supplied by default to all Users
 * and usually supplied by a Heritage organisation
 */

class HeritageController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Fetch all Heritages for an Album
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

     * Persist a new Heritage and assign it to an Album
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
     * Fetch a specific Heritage
     *
     * These are attached to Albums and therefore are User specific
     * @param HeritageRequest\Show $reqeust
     * @param $albumId
     * @param $heritageId
     * @return mixed
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
     * Update a specific Heritage
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
     * Remove the specified heritage
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
