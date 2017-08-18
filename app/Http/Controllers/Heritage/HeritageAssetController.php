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
     * @param  int  $assetId
     * @return \Illuminate\Http\Response
     */
    public function show(HeritageAssetRequest\Show $request, $assetId)
    {
        return response()->success([], 204, 'No Content');
    }
}
