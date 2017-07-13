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
    public function store(Request $request)
    {
        $data = $request->json()->all();
        $story = new Story;
        $story->title = $data['title'];
        $story->description = $data['description'];
        // making happened_at and file_name work for the demo, change this to actual data soon
        $story->happened_at = date("Y-m-d H:i:s");
        $story->file_name = str_replace(' ', '', $data['title']);
        $story->albums_id = 1;
        $story->users_id = 1;
        //$story->albums_id = $request->albums_id;
        //$story->users_id = $request->users_id;

        $story->save();
        return response()->json([
            'id' => $story->id,
            'title' => $story->title,
            'description' => $story->description
        ], 201);
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

        $story->title = $request->title;
        $story->description = $request->description;
        $story->happened_at = $request->happened_at;
        $story->file_name = $request->file_name;
        $story->albums_id = $request->albums_id;
        $story->users_id = $request->users_id;

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

    public function upload(Request $request, $id)
    {
        $story = Story::find($id);

        $PUBLIC_DIR = '/public';
        $UPLOADS_FOLDER = '/img/storyUploads/';

        $imageName = $story->id . '.' .$request->file('image')->getClientOriginalExtension();
        $location = base_path() . $PUBLIC_DIR . $UPLOADS_FOLDER;
        $request->file('image')->move($location, $imageName);

        $story->file_name = $UPLOADS_FOLDER . $imageName;
        return $story->file_name;
    }
}
