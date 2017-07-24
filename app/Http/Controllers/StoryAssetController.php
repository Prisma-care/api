<?php

namespace App\Http\Controllers;

use App\Story;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Image;

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

        //$this->retrieveItem('headers', $key, $default);

        $PUBLIC_DIR = '/public';
        $UPLOADS_FOLDER = '/img/storyUploads/';

        $extension = ($request->asset->extension())
                    ? ($request->asset->extension())
                    : pathinfo($request->asset, PATHINFO_EXTENSION);
         
        $assetName = $story->id . '.' . $extension;
        $location = base_path() . $PUBLIC_DIR . $UPLOADS_FOLDER;
        $request->file('asset')->move($location, $assetName);

        $story->asset_name = env('APP_URL') . $UPLOADS_FOLDER . $assetName;
        $story->save();

        $location = $story->asset_name;

        $this->resize($story->id, $extension);
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

    public function resize($id, $ext)
    {
        //get url
        $PUBLIC_DIR = '/public';
        $UPLOADS_FOLDER = '/img/storyUploads/';
        $fileUrl = '../' . $PUBLIC_DIR . $UPLOADS_FOLDER;

        //load original file
        $img = Image::make($fileUrl . $id . '.' . $ext);

        //make thumbs
        $img->fit(500, 500);
        
        //save thumbnail as new file
        $newName = $id . '_thumb.' . $ext;
        $img->save($fileUrl . $newName);
    }
}
