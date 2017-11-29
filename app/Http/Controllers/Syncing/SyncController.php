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

    public function runSync(Sync $sync)
    {
        $sync_id = $sync->id;
        $model_id = $sync->model_id;
        $model_type = $sync->model_type;

        Sync::where('id',$sync_id)->update('status','running');

        if ($model_type === 'Story') {

            $model = Story::where('id', $model_id)->get();

            // get the collection of 100 patients that don't have this asset
            // if there are none exit and update the sync as completed

        } else {

            $model = Album::where('id', $model_id)->get();

            // get the collection of 100 patients that don't have this asset
            // if there are none exit and update the sync as completed

            $patients = [];

            // replicate this into the relevant table with their ids

            foreach ($patients as $patient) {

                $newAlbum = $model->replicate();
                $newAlbum->patient_id = $patient->id;
                $newAlbum->source_album_id = $model->id;
                $newAlbum->save();

            }

        }

        if (count($patients < 100)){

            // if collection was less than 100 update the sync as completed
        }
    }
}
