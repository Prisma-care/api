<?php

namespace App\Utils;

use Image;

class ImageUtility
{
    /**
     * @param $image
     * @param $path
     * @param $assetName
     * @param $extension
     */
    public static function saveThumbs($image, $path, $assetName, $extension)
    {
        $assetName = $assetName . '_thumbs.' . $extension;
        $thumbs = Image::make($image->getRealPath());
        $thumbs->fit(500, 500);
        $thumbs->save(storage_path("app/$path/$assetName"));
    }
}
