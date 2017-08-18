<?php

namespace App\Http\Controllers;

use App\Heritage;
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
     * @return \Illuminate\Http\Response
     */
    public function store(HeritageAssetRequest\Store $request)
    {
        return response()->success([], 204, 'No Content');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\HeritageAsset\Show $request
     * @param  int  $heritageId
     * @param  int  $assetId
     * @return \Illuminate\Http\Response
     */
    public function show(HeritageAssetRequest\Show $request, $heritageId, $assetId)
    {
        Heritage::findOrFail($heritageId);

        $storagePath = storage_path("app/heritage/$heritageId/$asset");

        if (!File::exists($storagePath)) {
            return response()->exception('This asset does not exist.', 404);
        }

        $file = File::get($storagePath);
        $mimeType = File::mimeType($storagePath);

        return response($file, 200)->header("Content-Type", $mimeType);
        return response()->success([], 204, 'No Content');
    }
}
