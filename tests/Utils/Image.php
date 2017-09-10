<?php

namespace Tests\Utils;

use Image as ImageLib;

class Image
{
    public static function generate($path, $width = null, $height = null)
    {
        $img = ImageLib::canvas($width ?: 32, $height ?: 32, '#ccc');
        ImageLib::make($img)->save($path);
    }
}
