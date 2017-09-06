<?php

namespace App\Http\Controllers;

use App\Story;
use File;
use App\Http\Requests\Story as StoryRequest;

/**
 * Class StoryController
 * @package App\Http\Controllers
 * @resource Story
 *
 * Stories are made up of a photo or video and a short text.
 * They are used to stimulate discussion between the User and Patient
 * Stories are collected in Albums and a number of Heritage items are supplied by default
 * when a new User registers a Patient
 */

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
     * Persist a new Story
     *
     * @param StoryRequest\Store $request
     * @param $patientId
     * @return mixed
     */
    public function store(StoryRequest\Store $request, $patientId)
    {
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
     * Fetch a Story
     *
     * @param StoryRequest\Show $request
     * @param $patientId
     * @param Story $story
     * @return mixed
     */
    public function show(StoryRequest\Show $request, $patientId, Story $story)
    {
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
     * Update a Story
     *
     * @param StoryRequest\Update $request
     * @param $patientId
     * @param Story $story
     * @return mixed
     */
    public function update(StoryRequest\Update $request, $patientId, Story $story)
    {
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
     * Remove a story from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoryRequest\Destroy $request, $patientId, Story $story)
    {
        if ($story->delete()) {
            $directory = storage_path("app/stories/$patientId/$story->id");
            File::deleteDirectory($directory);
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception("The story could not be deleted", 500);
        }
    }
}
