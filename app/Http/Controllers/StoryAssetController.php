<?php

namespace App\Http\Controllers;

use Image;
use File;
use App\Story;
use App\Http\Requests\StoryAsset as StoryAssetRequest;

class StoryAssetController extends Controller
{
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
    public function store(StoryAssetRequest\Store $request, $patientId, $storyId)
    {
        $story = Story::findOrFail($storyId);

        if (!$request->hasFile('asset')) {
            return response()->exception('No asset was provided or the form-data request was malformed', 400);
        } elseif (!$request->file('asset')->isValid()) {
            return response()->exception('Asset upload failed, please try again later.', 500);
        }

        $asset = $request->file('asset');
        $extension = ($asset->extension())
                    ? ($asset->extension())
                    : pathinfo($asset, PATHINFO_EXTENSION);

        $assetName = $storyId;
        $fullAssetName = "$assetName.$extension";
        $storagePath = "stories/$patientId/$storyId";
        $asset->storeAs($storagePath, $fullAssetName);
        $this->saveThumbs($asset, $storagePath, $assetName, $extension);

        $story->asset_name = $request->url() . '/' . $fullAssetName;
        $story->save();

        $location = $story->asset_name;
        return response()->success(['id'=> $story->id], 201, 'Created', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

    private function saveThumbs($image, $path, $assetName, $extension)
    {
        $assetName = $assetName . '_thumbs.' . $extension;
        $thumbs = Image::make($image->getRealPath());
        $thumbs->fit(500, 500);
        $thumbs->save(base_path() . "/storage/app/$path/$assetName");
    }
}
