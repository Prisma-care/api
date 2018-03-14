<?php

namespace App\Http\Controllers\Syncing;

use App\Album;
use App\Heritage;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Story;
use App\Sync;
use App\User;
use Carbon\Carbon;

class SyncController extends Controller
{
    public function checkForSyncs()
    {
        try {
            $sync = Sync::whereIn('status', ['ready', 'running'])
                ->orderBy('created_at', 'ASC')
                ->firstOrFail();

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
        $batch_size = 100;

        Sync::where('id', $sync_id)->update(['status' => 'running']);

        if ($model_type === 'Story') {
            $model = Heritage::where('id', $model_id)->first();

            $all_albums = Album::where('patient_id', '>', 0)->take($batch_size)->pluck('id');
            $albums_with_this_story = Story::where(['is_heritage' => 1, 'heritage_id' => $model_id])->pluck('album_id');
            $balance = $all_albums->diff($albums_with_this_story);
            $albums = $balance->all();

            if (count($albums) === 0) {
                $sync->status = 'complete';
                $sync->finished_at = Carbon::now();
                $sync->save();

                return false;
            }

            foreach ($albums as $album) {
                $data = [

                    'asset_name' => $model->asset_name,
                    'asset_type' => $model->asset_type,
                    'description' => $model->description,
                    'happened_at' => null,
                    'user_id' => 1,
                    'is_heritage' => 1,
                    'album_id' => $album,
                    'heritage_id' => $model->id,

                ];

                Story::create($data);
            }

            if (count($albums < $batch_size)) {
                $sync->status = 'complete';
                $sync->finished_at = Carbon::now();
                $sync->save();

                return false;
            }
        } else {
            $model = Album::where('id', $model_id)->first();

            $all_patients = Patient::all()->pluck('id');
            $patients_with_this_album = Album::where('source_album_id', $model_id)->pluck('patient_id');
            $balance = $all_patients->diff($patients_with_this_album);
            $patients = $balance->all();

            if (count($patients) === 0) {
                $sync->status = 'complete';
                $sync->finished_at = Carbon::now();
                $sync->save();

                return false;
            }

            foreach ($patients as $patient) {
                $newAlbum = $model->replicate();
                $newAlbum->patient_id = $patient;
                $newAlbum->source_album_id = $model->id;
                $newAlbum->save();
            }
        }

        if (count($patients < $batch_size)) {
            $sync->status = 'complete';
            $sync->finished_at = Carbon::now();
            $sync->save();

            return false;
        }
    }

    public function timeMachine()
    {
        // revert Thor back to hasNew === true
        User::whereIn('id', [479, 482])->update(['last_login' => '2017-11-01']);
    }
}
