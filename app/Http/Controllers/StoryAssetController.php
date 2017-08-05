<?php

namespace App\Http\Controllers;

use Image;
use App\Story;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;

class StoryAssetController extends Controller
{
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
        //
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
    public function store(Request $request, $patientId, $storyId)
    {
        try {
            Patient::findOrFail($patientId);
            Story::findOrFail($storyId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            return response()->exception("There is no $failingResource resource with the provided id.", 400);
        }

        $story = Story::find($storyId);

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
        $storagePath = "stories/$patientId/$storyId";
        $asset->storeAs($storagePath, "$assetName.$extension");
        $this->saveThumbs($asset, $storagePath, $assetName, $extension);

        $story->asset_name = $request->url() . '/' . $assetName;
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function saveThumbs($image, $path, $assetName, $extension)
    {
        $assetName = $assetName . '_thumbs.' . $extension;
        $thumbs = Image::make($image->getRealPath());
        $thumbs->fit(500, 500);
        $thumbs->save(base_path() . "/storage/app/$path/$assetName");
    }
}
