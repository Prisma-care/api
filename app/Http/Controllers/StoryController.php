<?php

namespace App\Http\Controllers;

use App\Story;
use App\Http\Requests\Story as StoryRequest;

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
     * Display the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoryRequest\Destroy $request, $patientId, Story $story)
    {
        if ($story->delete()) {
            return response()->success([], 200, 'OK');
        } else {
            return response()->exception("The story could not be deleted", 500);
        }
    }
}
