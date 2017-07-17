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
        $story->title = $request->input('title');
        $story->description = $request->input('description');
        // making happened_at and file_name work for the demo, change this to actual data soon
        $story->happened_at = date('Y-m-d H:i:s');
        $story->file_name = str_replace(' ', '', $request->input('title'));
        $story->albums_id = 1;
        $story->users_id = 1;
        //$story->albums_id = $request->input('albums_id');
        //$story->users_id = $request->input('users_id');

        $story->save();

        $responseCode = 201;
        $createdStory = [
            'id' => $story->id,
            'title' => $story->title,
            'description' => $story->description
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => env('APP_URL') . '/patient/'. $patientId .'/story/' . $story->id
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
    public function show(Story $story)
    {
        $story = Story::find($story);

        return $story;
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

    public function upload(Request $request, $patientId, $storyId)
    {
        $story = Story::find($storyId);

        $PUBLIC_DIR = '/public';
        $UPLOADS_FOLDER = '/img/storyUploads/';

        $imageName = $story->id . '.' . $request->file('image')->getClientOriginalExtension();
        $location = base_path() . $PUBLIC_DIR . $UPLOADS_FOLDER;
        $request->file('image')->move($location, $imageName);

        $story->file_name = $UPLOADS_FOLDER . $imageName;

        $responseCode = 201;
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => env('APP_URL') . $story->file_name
            ],
            'response' => [
                'id' => $story->id
            ]
        ];
        return response()->json($response, $responseCode);
    }
}
