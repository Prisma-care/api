<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Album;
use App\Patient;
use App\User;
use App\Http\Requests\Album as AlbumRequest;

/**
 * Class AlbumController
 * @package App\Http\Controllers
 * @resource Album
 *
 * Controller used return, persist and remove Album data for particular Patients
 * Albums are collections of visual content used to stimulate discussions between families and their loved ones
 */
class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    private $keyTranslations = array(
        'id' => 'id',
        'title' => 'title',
        'description' => 'description',
        'isHeritage' => 'is_heritage',
        'patientId' => 'patient_id',
        'userId' => 'user_id',
        'updatedAt' => 'updated_at',
        'createdAt' => 'created_at'
    );


    /**
     * Returns an array of Albums belonging to a particular Patient
     *
     * @param AlbumRequest\Index $request
     * @param $patientId
     * @return mixed
     */
    public function index(AlbumRequest\Index $request, $patientId)
    {
        $last_login = Auth::user()->last_login;
        $user_id = Auth::user()->id;
        $now = Carbon::now();
        User::where('id', $user_id)->update(['last_login' => $now]);

        $albums = Patient::find($patientId)->albums;
        $allAlbums = [];
        foreach ($albums as $album) {
            $thisAlbum = [
                'id' => $album->id,
                'title' => $album->title,
                'patientId' => $album->patient_id,
                'hasNew' => false,
                'stories' => []
            ];
            $stories = Album::find($album->id)->stories;
            foreach ($stories as $story) {
                $thisAlbum['stories'][] = [
                    'id' => $story->id,
                    'description' => $story->description,
                    'type' => $story->asset_type,
                    'favorited' => $story->favorited,
                    'source' => $story->asset_name,
                    'isHeritage' => $story->is_heritage,
                    'userId' => $story->user_id,
                    'updatedAt' => $story->updated_at,
                    'createdAt' => $story->created_at

                ];

                if (!is_null($last_login) && ($last_login <= $story->created_at) && $thisAlbum['hasNew']===false) {
                    $thisAlbum['hasNew'] = true;
                }
            }
            $allAlbums[] = $thisAlbum;
        }

        return response()->success($allAlbums, 200, 'OK');
    }


    /**
     *  Persists an Album to storage for a particular Patient
     *
     * @param AlbumRequest\Store $request
     * @param $patientId
     * @return mixed
     */
    public function store(AlbumRequest\Store $request, $patientId)
    {
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
     *  Returns an Album belonging to a particular Patient
     *
     * @param AlbumRequest\Show $request
     * @param $patientId
     * @param $albumId
     * @return mixed
     */
    public function show(AlbumRequest\Show $request, $patientId, $albumId)
    {
        $album = Album::findOrFail($albumId);
        $thisAlbum = [
            'id' => $album->id,
            'title' => $album->title,
            'patient_id' => $patientId,
            'stories' => []
        ];
        $stories = Album::find($album->id)->stories;
        foreach ($stories as $story) {
            $thisAlbum['stories'][] = [
                'id' => $story->id,
                'description' => $story->description,
                'type' => $story->asset_type,
                'favorited' => $story->favorited,
                'source' => $story->asset_name,
                'isHeritage' => $story->is_heritage,
                'userId' => $story->user_id,
                'updatedAt' => $story->updated_at,
                'createdAt' => $story->created_at
            ];
        }

        return response()->success($thisAlbum, 200, 'OK');
    }


    /**
     *  Updates an Album belonging to particular Patient
     *
     * @param AlbumRequest\Update $request
     * @param $patientId
     * @param $albumId
     * @return mixed
     */
    public function update(AlbumRequest\Update $request, $patientId, $albumId)
    {
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
     *  Removes an Album from storage
     *
     * @param AlbumRequest\Destroy $request
     * @param $patientId
     * @param $albumId
     * @return mixed
     */
    public function destroy(AlbumRequest\Destroy $request, $patientId, $albumId)
    {
        $album = Album::findOrFail($albumId);
        if ($album->delete()) {
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception('The album could not be deleted', 500);
        }
    }
}
