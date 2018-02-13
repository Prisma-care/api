<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoryAsset as StoryAssetRequest;
use App\Story;
use App\Utils\ImageUtility;

/**
 * Class StoryAssetController.
 *
 * @resource StoryAsset
 *
 * StoryAssets are photographic and video materials used to stimulate discussion between the User and Patient
 */
class StoryAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    private function attachImageAsset($request, $story, $patientId)
    {
        $asset = $request->file('asset');
        $extension = ($asset->extension())
                    ? ($asset->extension())
                    : pathinfo($asset, PATHINFO_EXTENSION);

        $assetName = $story->id;
        $fullAssetName = "$assetName.$extension";
        $storagePath = "stories/$patientId/$story->id";
        $asset->storeAs($storagePath, $fullAssetName);
        ImageUtility::saveThumbs($asset, $storagePath, $assetName, $extension);

        $story->asset_type = 'image';
        $story->asset_name = $request->url().'/'.$fullAssetName;
        $story->save();
    }

    /**
     * Store a new StoryAsset and attach it to a Story.
     *
     * StoryAssets can be photos or URLs to videos on external services such as YouTube or Vimeo
     *
     * @param StoryAssetRequest\Store $request
     * @param $patientId
     * @param $storyId
     *
     * @return mixed
     */
    public function store(StoryAssetRequest\Store $request, $patientId, $storyId)
    {
        $story = Story::findOrFail($storyId);
        $assetType = $request->input('assetType');
        if (! $assetType || $assetType === 'image') {
            if (! $request->hasFile('asset')) {
                return response()->exception('No asset was provided or the form-data request was malformed', 400);
            } elseif (! $request->file('asset')->isValid()) {
                return response()->exception('Asset upload failed, please try again later.', 500);
            }

            $this->attachImageAsset($request, $story, $patientId);
            $location = $story->asset_name;

            return response()->success(['id' => $story->id], 201, 'Created', $location);
        } elseif ($assetType === 'youtube') {
            $story->asset_name = $request->input('asset');
            $story->asset_type = 'youtube';
            $story->save();

            $location = $request->url().'/'.$story->id;

            return response()->success(['id' => $story->id], 201, 'Created', $location);
        }
    }


    /**
     * Fetch a particular StoryAsset.
     * @param StoryAssetRequest\Show $request
     * @param $patientId
     * @param $storyId
     * @param $asset
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function show(StoryAssetRequest\Show $request, $patientId, $storyId, $asset)
    {
        $story = Story::findOrFail($storyId);
        if ($story->asset_type === 'youtube') {
            return response()->success([
                'id' => $story->id,
                'source' => $story->asset_name,
                'type' => 'youtube',
            ], 200, 'OK');
        }

        $storagePath = "stories/$patientId/$storyId/$asset";
        if (! Storage::exists($storagePath)) {
            return response()->exception('This asset does not exist.', 404);
        }

        $file = Storage::get($storagePath);
        $mimeType = Storage::mimeType($storagePath);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
