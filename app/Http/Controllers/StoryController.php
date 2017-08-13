<?php

namespace App\Http\Controllers;

use Validator;
use App\Story;
use App\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoryController extends Controller
{
    private $keyTranslations = array(
        'id' => 'id',
        'description' => 'description',
        'happenedAt' => 'happened_at',
        'favorited' => 'favorited',
        'creatorId' => 'user_id',
        'albumId' => 'album_id'
    );

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStory $request, $patientId)
    {
        Patient::findOrFail($patientId);
        $story = new Story([
            'description' => $request->input('description'),
            'happened_at' => $request->input('happenedAt'),
            'asset_name' => null,
            // NYI
            'asset_type' => null,
            'user_id' => $request->input('creatorId'),
            'album_id' => $request->input('albumId')
        ]);
        if (!$story->save()) {
            return response()->exception('The story could not be created', 500);
        }

        $createdStory = [
            'id' => $story->id,
            'description' => $story->description,
            'happenedAt' => $story->happened_at,
            'albumId' => $story->album_id,
            'creatorId' => $story->user_id,
            'favorited' => false
        ];

        $location = $request->url() . '/' . $story->id;
        return response()->success($createdStory, 201, 'Created', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show($patientId, $storyId)
    {
        Patient::findOrFail($patientId);
        $story = Story::findOrFail($storyId);
        $gotStory = [
            'id' => $story->id,
            'description' => $story->description,
            'happenedAt' => $story->happened_at,
            'albumId' => $story->album_id,
            'creatorId' => $story->user_id,
            'assetSource' => $story->asset_name,
            'favorited' => $story->favorited
        ];

        return response()->success($gotStory, 200, 'OK');
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
            return response()->exception("Method not allowed", 405);
        }

        Patient::findOrFail($patientId);
        $story = Story::findOrFail($storyId);

        $values = $request->all();
        foreach (array_keys($values) as $key) {
            $translatedKey = (isset($this->keyTranslations[$key]))
                                ? $this->keyTranslations[$key]
                                : null;
            if ($translatedKey) {
                $story[$translatedKey] = $values[$key];
            }
        }
        if (!$story->update()) {
            return response()->exception("The story could not be updated", 500);
        }

        return response()->success([], 200, 'OK');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy($patientId, $storyId)
    {
        $story = Story::findOrFail($storyId);
        if ($story->delete()) {
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception("The story could not be deleted", 500);
        }
    }
}
