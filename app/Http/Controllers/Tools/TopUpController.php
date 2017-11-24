<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\User;
use App\Album;
use App\Heritage;
use App\Story;
use App\Patient;

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

    /* series of one use catch up methods */

    public function sourceOriginalAlbums()
    {
        $default_albums = Album::with('heritage')->get()->where('patient_id','=',null)->values()->all();

        foreach ($default_albums as $default_album) {

            Album::where('title',$default_album->title)
                ->where('patient_id','>',0)
                ->update(['source_album_id' => $default_album->id]);
        }
    }


    public function sourceOriginalHeritage()
    {
        $heritages = Heritage::all();

        foreach ($heritages as $heritage) {

            Story::where('description', $heritage->description)
                ->where('is_heritage',1)
                ->update(['heritage_id' => $heritage->id]);
        }
    }

    /*
     * Fast Forward
     * get all the albums with no patientID
     * loop each and match title. update source_album_id
     *
     * get all heritage
     * loop. Match description to story description and update heritage_id
     *
     *
     *
     * Incremental
     *
     *
     *
     *
     *
     *
     */
}
