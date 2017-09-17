<?php

namespace App\Http\Controllers\Heritage;

use App\Album;
use App\Heritage;
use App\Utils\ImageUtility;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HeritageAsset as HeritageAssetRequest;

/**
 * Class HeritageAssetController
 * @package App\Http\Controllers\Heritage
 * @resource Heritage\HeritageAsset
 *
 * HeritageAssets are photographic and video materials used to stimulate discussion between the User and Patient
 * HeritageAssets are supplied by local heritage organisations rather than from the Users
 */

class HeritageAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * @param $request
     * @param $heritage
     */
    private function attachImageAsset($request, $heritage)
    {
        $asset = $request->file('asset');
        $extension = ($asset->extension()) ? ($asset->extension()) : pathinfo($asset, PATHINFO_EXTENSION);

        $assetName = $heritage->id;
        $fullAssetName = "$assetName.$extension";
        $storagePath = "heritage/$heritage->id";
        $asset->storeAs($storagePath, $fullAssetName);
        ImageUtility::saveThumbs($asset, $storagePath, $assetName, $extension);

        $location = $request->url() . '/' . $fullAssetName;
        $heritage->asset_name = $location;
        $heritage->asset_type = 'image';
        $heritage->save();
    }


    /**
     * Store a new HeritageAsset and attach it to a Heritage
     *
     * HeritageAssets can be photos or URLs to videos on external services such as YouTube or Vimeo
     *
     * @param HeritageAssetRequest\Store $request
     * @param $albumId
     * @param $heritageId
     * @return mixed
     */
    public function store(HeritageAssetRequest\Store $request, $albumId, $heritageId)
    {
        Album::findOrFail($albumId);
        $heritage = Heritage::findOrFail($heritageId);
        $assetType = $request->input('assetType');
        if (!$assetType || $assetType === 'image') {
            if (!$request->hasFile('asset')) {
                return response()->exception('No asset was provided or the form-data request was malformed', 400);
            } elseif (!$request->file('asset')->isValid()) {
                return response()->exception('Asset upload failed, please try again later.', 500);
            }

            $this->attachImageAsset($request, $heritage);
            $location = $heritage->asset_name;
            return response()->success(['id'=> $heritage->id], 201, 'Created', $location);
        } elseif ($assetType === 'youtube') {
            $heritage->asset_name = $request->input('asset');
            $heritage->asset_type = 'youtube';
            $heritage->save();

            $location = $request->url() . '/' . $heritage->id;
            return response()->success([
              'id'=> $heritage->id,
              'source' => $heritage->asset_name,
              'type' => 'youtube'
            ], 201, 'Created', $location);
        }
    }


    /**
     * Fetch a particular HeritageAsset
     *
     * @param HeritageAssetRequest\Show $request
     * @param $albumId
     * @param $heritageId
     * @param $assetId
     * @return mixed
     */
    public function show(HeritageAssetRequest\Show $request, $albumId, $heritageId, $assetId)
    {
        Album::findOrFail($albumId);
        $heritage = Heritage::findOrFail($heritageId);
        if ($heritage->asset_type === 'youtube') {
            return response()->success([
                'id' => $heritage->id,
                'source' => $heritage->asset_name,
                'type' => 'youtube'
            ], 200, 'OK');
        }

        $storagePath = "heritage/$heritageId/$assetId";
        if (!Storage::exists($storagePath)) {
            return response()->exception('This asset does not exist.', 404);
        }

        $file = Storage::get($storagePath);
        $mimeType = Storage::mimeType($storagePath);

        return response($file, 200)->header("Content-Type", $mimeType);
    }
}
