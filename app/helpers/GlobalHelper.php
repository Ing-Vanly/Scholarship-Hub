<?php

if (!function_exists('getImagePath')) {
    function getImagePath($imageName, $path, $default = 'default-image.png')
    {
        if ($imageName && file_exists(public_path('uploads/' . $path . '/' . $imageName))) {
            return asset('uploads/' . $path . '/' . $imageName);
        }

        return asset('uploads/' . ltrim($default, '/'));
    }
}

if (!function_exists('getPdfPath')) {
    function getPdfPath($fileName, $path, $default = 'thumb-pdf.png')
    {
        if ($fileName && file_exists(public_path('uploads/' . $path . '/' . $fileName))) {
            return asset('uploads/' . $path . '/' . $fileName);
        }

        return asset('uploads/' . ltrim($default, '/'));
    }
}
