<?php

namespace App\Http\Controllers;

use Image;
use App\Story;
use App\Patient;
use App\Utils\ImageUtility;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoryAsset as StoryAssetRequest;

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

        $story->asset_name = $request->url() . '/' . $fullAssetName;
        $story->save();
    }

    /**
     * Store a newly created resource in storage.
     * @param StoryAssetRequest\Store $request
     * @param $patientId
     * @param $storyId
     * @return mixed
     */
    public function store(StoryAssetRequest\Store $request, $patientId, $storyId)
    {
        $story = Story::findOrFail($storyId);
        $assetType = $request->input('assetType');
        if (!$assetType || $assetType === 'image') {
            if (!$request->hasFile('asset')) {
                return response()->exception('No asset was provided or the form-data request was malformed', 400);
            } elseif (!$request->file('asset')->isValid()) {
                return response()->exception('Asset upload failed, please try again later.', 500);
            }

            $this->attachImageAsset($request, $story, $patientId);
            $story->asset_type = 'image';
            $location = $story->asset_name;
            return response()->success(['id'=> $story->id], 201, 'Created', $location);
        } elseif ($assetType === 'youtube') {
            return response()->success([], 204, 'No Content');
            $story->asset_type = 'youtube';
        }
    }

    /**
     * @param StoryAssetRequest\Show $request
     * @param $patientId
     * @param $storyId
     * @param $asset
     * @return mixed
     */
    public function show(StoryAssetRequest\Show $request, $patientId, $storyId, $asset)
    {
        Story::findOrFail($storyId);

        $storagePath = storage_path("app/stories/$patientId/$storyId/$asset");

        if (!File::exists($storagePath)) {
            return response()->exception('This asset does not exist.', 404);
        }

        $file = File::get($storagePath);
        $mimeType = File::mimeType($storagePath);

        return response($file, 200)->header("Content-Type", $mimeType);
    }
}
