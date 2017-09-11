<?php

namespace Tests\Utils;

use Image as ImageLib;

class Image
{
    public static function generate($extension, $width = null, $height = null)
    {
        $img = ImageLib::canvas($width ?: 32, $height ?: 32, '#ccc');
        return (string) ImageLib::make($img)->encode($extension);
    }
}
