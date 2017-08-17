<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Heritage;

class HeritageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($heritageId)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
