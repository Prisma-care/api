<?php

namespace App\Http\Controllers\Syncing;

use App\Http\Controllers\Controller;
use App\Album;
use App\Story;
use App\Sync;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkForSyncs()
    {
        try {

            $sync = Sync::whereIn('status', ['ready', 'running'])->findOrFail();
            $this->runSync($sync);

        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            // there are no sync jobs to run

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Sync $sync
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sync $sync)
    {
        //
    }

}
