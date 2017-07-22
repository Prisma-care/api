<?php

namespace App\Http\Controllers;

use App\Story;
use App\Profile;
use App\Exceptions\JsonException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            Profile::findOrFail($patientId);
            Story::findOrFail($storyId);
        } catch (ModelNotFoundException $e) {
            $failingResource = class_basename($e->getModel());
            throw new JsonException("There is no $failingResource resource with the provided id.", 400);
        }

        $story = Story::find($storyId);

        if (!$request->hasFile('asset')) {
            throw new JsonException('No asset was provided or the form-data request was malformed', 400);
        } elseif (!$request->file('asset')->isValid()) {
            throw new JsonException('Asset upload failed, please try again later.', 500);
        }

        //$this->retrieveItem('headers', $key, $default);

        $PUBLIC_DIR = '/public';
        $UPLOADS_FOLDER = '/img/storyUploads/';

        $extension = ($request->asset->extension())
                    ? ($request->asset->extension())
                    : pathinfo(storage_path() .'/uploads/categories/featured_image.jpg', PATHINFO_EXTENSION);
         
        $assetName = $story->id . '.' . $extension;
        $location = base_path() . $PUBLIC_DIR . $UPLOADS_FOLDER;
        $request->file('asset')->move($location, $assetName);

        $story->file_name = env('APP_URL') . $UPLOADS_FOLDER . $assetName;
        $story->save();

        $location = $story->file_name;
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
}
