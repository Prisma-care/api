<?php

namespace App\Http\Controllers;

use App\Heritage;
use App\Utils\ImageUtility;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HeritageAsset as HeritageAssetRequest;

class HeritageAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HeritageAsset\Show $request
     * @param  int $heritageId
     * @return \Illuminate\Http\Response
     */
    public function store(HeritageAssetRequest\Store $request, $albumId, $heritageId)
    {
        $heritage = Heritage::findOrFail($heritageId);

        if (!$request->hasFile('asset')) {
            return response()->exception('No asset was provided or the form-data request was malformed', 400);
        } elseif (!$request->file('asset')->isValid()) {
            return response()->exception('Asset upload failed, please try again later.', 500);
        }

        $asset = $request->file('asset');
        $extension = ($asset->extension()) ? ($asset->extension()) : pathinfo($asset, PATHINFO_EXTENSION);

        $assetName = $heritageId;
        $fullAssetName = "$assetName.$extension";
        $storagePath = "heritage/$heritageId";
        $asset->storeAs($storagePath, $fullAssetName);
        ImageUtility::saveThumbs($asset, $storagePath, $assetName, $extension);

        $location = $request->url() . '/' . $fullAssetName;
        $heritage->asset_name = $location;
        $heritage->save();
        return response()->success(['id'=> $heritage->id], 201, 'Created', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\HeritageAsset\Show $request
     * @param  int  $heritageId
     * @param  int  $assetId
     * @return \Illuminate\Http\Response
     */
    public function show(HeritageAssetRequest\Show $request, $albumId, $heritageId, $assetId)
    {
        $heritage = Heritage::findOrFail($heritageId);
        if ($heritage->asset_type === 'youtube') {
            return response()->success([
                'id' => $heritage->id,
                'source' => $heritage->asset_name,
                'type' => 'youtube'
            ], 200, 'OK');
        }
        $storagePath = storage_path("app/heritage/$heritageId/$assetId");

        if (!File::exists($storagePath)) {
            return response()->exception('This asset does not exist.', 404);
        }

        $file = File::get($storagePath);
        $mimeType = File::mimeType($storagePath);

        return response($file, 200)->header("Content-Type", $mimeType);
    }
}
