<?php

namespace App\Http\Controllers;

use App\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stories = Story::all();
        return $stories->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $patientId)
    {
        $story = new Story;
        $story->description = $request->input('description');
        $story->title = $request->input('title') ? $request->input('title') : null;
        $story->happened_at = $request->input('happened_at')
            ? $request->input('happened_at')
            : null;
        $story->file_name = null;
        $story->users_id = $request->input('creatorId');
        $story->albums_id = $request->input('albumId');

        $story->save();

        $responseCode = 201;
        $createdStory = [
            'id' => $story->id,
            'description' => $story->description,
            'title' => $story->title,
            'happened_at' => $story->happened_at,
            'albumId' => $story->albums_id,
            'creatorId' => $story->users_id
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => env('APP_URL') . '/patient/'. $patientId . '/story/' . $story->id
            ],
            'response' => $createdStory
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show($patientId, $storyId)
    {
        $story = Story::find($storyId);
        $responseCode = 200;
        $gotStory = [
            'id' => $story->id,
            'description' => $story->description,
            'title' => $story->title,
            'happenedAt' => $story->happened_at,
            'albumId' => $story->albums_id,
            'creatorId' => $story->users_id,
            'assetSource' => $story->file_name,
            // TODO update fixture after implementation
            'favorited' => false
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'OK'
            ],
            'response' => $gotStory
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function edit(Story $story)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $story = Story::find($story);

        $story->title = $request->input('title');
        $story->description = $request->input('description');
        $story->happened_at = $request->input('happened_at');
        $story->file_name = $request->input('file_name');
        $story->albums_id = $request->input('albums_id');
        $story->users_id = $request->input('users_id');

        $story->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        Story::destroy($story);
    }
}
