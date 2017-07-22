<?php

namespace App\Http\Controllers;

use Validator;
use App\Profile;
use App\Story;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoryController extends Controller
{
    private $keyTranslations = array(
        'id' => 'id',
        'title' => 'title',
        'description' => 'description',
        'happenedAt' => 'happened_at',
        'creatorId' => 'users_id',
        'albumId' => 'albums_id'
    );

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

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
        try {
            Profile::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->json([
                'code' => 400,
                'message' => "There is no $failingResource resource with the provided id."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'creatorId' => 'required',
            'albumId' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()
            ]);
        }

        $story = new Story([
            'description' => $request->input('description'),
            'title' => $request->input('title'),
            'happened_at' => $request->input('happenedAt'),
            'file_name' => str_replace(' ', '', $request->input('title')),
            'users_id' => $request->input('creatorId'),
            'albums_id' => $request->input('albumId')
        ]);
        if (!$story->save()) {
            return response()->json([
                'code' => 500,
                'message' => 'The story could not be created'
            ]);
        }

        $responseCode = 201;
        $createdStory = [
            'id' => $story->id,
            'description' => $story->description,
            'title' => $story->title,
            'happenedAt' => $story->happened_at,
            'albumId' => $story->albums_id,
            'creatorId' => $story->users_id
        ];
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'Created',
                'location' => $request->url() . '/' . $story->id
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
        try {
            Profile::findOrFail($patientId);
            Story::findOrFail($storyId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->json([
                'code' => 400,
                'message' => "There is no $failingResource resource with the provided id."
            ]);
        }

        $story = Story::find($storyId)->first();

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
    public function update(Request $request, $patientId, $storyId)
    {
        if (!$request->isMethod('PATCH')) {
            return response()->json([
                'code' => 405,
                'message' => "Method not allowed"
            ]);
        }

        try {
            Profile::findOrFail($patientId);
            Story::findOrFail($storyId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->json([
                'code' => 400,
                'message' => "There is no $failingResource resource with the provided id."
            ]);
        }

        $story = Story::find($storyId);
        $values = array_filter($request->all());

        foreach (array_keys($values) as $key) {
            $translatedKey = (isset($this->keyTranslations[$key]))
                                ? $this->keyTranslations[$key]
                                : null;
            if ($translatedKey) {
                $story[$translatedKey] = $values[$key];
            }
        }

        if (!$story->update()) {
            return response()->json([
                'code' => 500,
                'message' => "The story could not be updated"
            ]);
        }

        $responseCode = 200;
        $response = [
            'meta' => [
                'code' => $responseCode,
                'message' => 'OK'
            ],
            'response' => []
        ];
        return response()->json($response, $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy($patienId, $storyId)
    {
        if (Story::destroy($storyId)) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'OK'
                ],
                'response' => []
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'message' => "The story could not be deleted"
            ]);
        }
    }
}
