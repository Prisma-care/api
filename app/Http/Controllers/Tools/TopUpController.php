<?php

namespace App\Http\Controllers\Tools;

use App\Album;
use App\Heritage;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Story;

class TopUpController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * No explicit return
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param int $patientId
     * @param int $albumId
     * @return bool
     */
    public function patientHasAlbum(int $patientId, int $albumId)
    {
        $album = Album::where('patient_id', $patientId)->where('source_album_id', $albumId)->first();

        if (!$album) :
            return false;
        else :
            return true;
        endif;
    }

    /**
     * @param int $patientAlbumId
     * @param int $heritageId
     * @return bool
     */
    public function albumHasHeritage(int $patientAlbumId, int $heritageId)
    {
        $story = Story::where('album_id', $patientAlbumId)->where('heritage_id', $heritageId)->first();

        if (!$story) :
            return false;
        else :
            return true;
        endif;
    }

    /* series of one use catch up methods */

    public function sourceOriginalAlbums()
    {
        $default_albums = Album::with('heritage')->get()->where('patient_id', '=', null)->values()->all();

        foreach ($default_albums as $default_album) {

            Album::where('title', $default_album->title)
                ->where('patient_id', '>', 0)
                ->update(['source_album_id' => $default_album->id]);
        }
    }


    public function sourceOriginalHeritage()
    {
        $heritages = Heritage::all();

        foreach ($heritages as $heritage) {

            Story::where('description', $heritage->description)
                ->where('is_heritage', 1)
                ->update(['heritage_id' => $heritage->id]);
        }
    }


    public function addNewHeritage()
    {

        $new_heritages = Heritage::where('created_at', '>', '2017-11-18')->get();

        $patients = Patient::has('albums')->get();

        foreach ($patients as $patient) {

            $patient_id = $patient->id;

            foreach ($new_heritages as $new_heritage) {

                $heritage_album_id = $new_heritage->album_id;

                $patient_album = Album::where('patient_id', $patient_id)
                    ->where('source_album_id', $heritage_album_id)->first();

                if ($patient_album) {

                    $patient_album_id = $patient_album->id;

                    Story::firstOrCreate(

                        ['heritage_id' => $new_heritage->id, 'album_id' => $patient_album_id],

                        [
                            'description' => $new_heritage->description,
                            'asset_name' => $new_heritage->asset_name ?: null,
                            'asset_type' => $new_heritage->asset_type ?: null,
                            'user_id' => 1,
                            'is_heritage' => true,
                            'album_id' => $patient_album_id,
                            'heritage_id' => $heritage_album_id
                        ]
                    );
                }
            }
        }
    }
}
