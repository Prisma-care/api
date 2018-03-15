<?php

namespace App\Http\Controllers\Heritage;

use App\Album;
use App\Heritage;
use App\Http\Controllers\Controller;
use App\Http\Requests\DefaultAlbum as DefaultAlbumRequest;
use App\Sync;

/**
 * Class DefaultAlbumController.
 *
 * @resource Heritage\DefaultAlbum
 *
 * When a Patient is created, a default set of Heritage Albums are generated and assigned to that Patient
 * This ensures that there is content available for the new User/Patient
 * Because the generated Heritage Albums are assigned to the Patient the can also be deleted
 */
class DefaultAlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Fetch Heritage Albums.
     *
     * @param DefaultAlbumRequest\Index $request
     *
     * @return mixed
     */
    public function index(DefaultAlbumRequest\Index $request)
    {
        $albums = Album::with('heritage')->get()
            ->where('patient_id', '=', null)
            ->values()
            ->all();

        return response()->success($albums, 200, 'OK');
    }

    /**
     * Fetch a single Heritage Album.
     *
     * @param \App\Http\Requests\DefaultAlbum\Show $request
     * @param int $albumId
     *
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
     * Persist a new Heritage Album.
     *
     * @param DefaultAlbumRequest\Store $request
     *
     * @return mixed
     */
    public function store(DefaultAlbumRequest\Store $request)
    {
        $album = new Album([
            'title' => $request->input('title'),
        ]);
        if (! $album->save()) {
            return response()->exception('The default album could not be created', 500);
        }

        Sync::create(['model_type' => 'Album', 'model_id' => $album->id]);

        $location = $request->url().'/'.$album->id;

        return response()->success($album, 201, 'Created', $location);
    }

    /**
     * Update a specific DefaultAlbum.
     *
     * @param DefaultAlbumRequest\Update $request
     * @param $albumId
     *
     * @return mixed
     */
    public function update(DefaultAlbumRequest\Update $request, $albumId)
    {
        $album = Album::findOrFail($albumId);
        if (! $album->isDefault()) {
            return response()->exception("The album you're trying to update is not a default album", 400);
        }
        $album->title = $request->input('title') ?: $album->title;
        if (! $album->update()) {
            return response()->exception('The album could not be updated', 500);
        }

        return response()->success([], 200, 'OK');
    }

    /**
     * Remove a specific DefaultAlbum.
     *
     * @param DefaultAlbumRequest\Destroy $request
     * @param $albumId
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function destroy(DefaultAlbumRequest\Destroy $request, $albumId)
    {
        $album = Album::findOrFail($albumId);
        if (! $album->isDefault()) {
            return response()->exception("The album you're trying to delete is not a default album", 400);
        }

        if ($album->delete()) {
            Heritage::where('album_id', $albumId)->delete();
            Sync::where(['model_type' => 'Album', 'model_id' => $albumId])->delete();

            return response()->success([], 200, 'OK');
        } else {
            return response()->exception('The album could not be deleted', 500);
        }
    }
}
