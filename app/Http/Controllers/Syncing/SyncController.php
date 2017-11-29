<?php

namespace App\Http\Controllers\Syncing;

use App\Album;
use App\Http\Controllers\Controller;
use App\Story;
use App\Sync;

class SyncController extends Controller
{
    protected $batch_size = 100;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkForSyncs()
    {
        try {

            $sync = Sync::whereIn('status', ['ready', 'running'])
                ->orderBy('created_at', 'ASC')
                ->findOrFail();

            $this->runSync($sync);

        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // there are no sync jobs to run

        }
    }

    /**
     * @param Sync $sync
     */
    public function runSync(Sync $sync)
    {
        $sync_id = $sync->id;
        $model_id = $sync->model_id;
        $model_type = $sync->model_type;

        Sync::where('id', $sync_id)->update('status', 'running');

        if ($model_type === 'Story') {

            $model = Story::where('id', $model_id)->get();

            $all_albums = Album::where('patient_id','>',0)->take($batch_size)->pluck('id');
            $albums_with_this_story = Story::where(['is_heritage' => 1, 'heritage_id' => $model_id])->pluck('album_id');
            $balance = $all_albums->diff($albums_with_this_story);
            $albums = $balance->all();

            if ($albums->count() === 0) {

                $sync->status = 'complete';
                $sync->save();

            }

            // get the collection of 100 patients that don't have this asset
            // if there are none exit and update the sync as completed

            // replicate this into the relevant table with their ids

            foreach ($albums as $album) {

                $newStory = $model->replicate();
                $newStory->user_id = 1;
                $newStory->album_id = $album;
                $newStory->heritage_id = $model->id;
                $newStory->save();
            }

            // get the patient album id

            if (count($patients < $batch_size)) {

                $sync->status = 'complete';
                $sync->save();
            }

        } else {

            $model = Album::where('id', $model_id)->get();

            // get the collection of 100 patients that don't have this asset
            // if there are none exit and update the sync as completed

            $all_patients = Patient::all()->pluck('id');
            $patients_with_this_album = Album::where('source_album_id', $model_id)->pluck('patient_id');
            $balance = $all_patients->diff($patients_with_this_album);
            $patients = $balance->all();

            if ($patients->count() === 0) {

                $sync->status = 'complete';
                $sync->save();

            }

            // replicate this into the relevant table with their ids

            foreach ($patients as $patient) {

                $newAlbum = $model->replicate();
                $newAlbum->patient_id = $patient->id;
                $newAlbum->source_album_id = $model->id;
                $newAlbum->save();
            }
        }

        if (count($patients < $batch_size)) {

            $sync->status = 'complete';
            $sync->save();
        }
    }
}
