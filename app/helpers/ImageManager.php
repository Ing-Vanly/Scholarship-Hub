<?php

namespace App\helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageManager
{
    public static function upload(string $dir, ?UploadedFile $image = null): ?string
    {
        if (! $image) {
            return null;
        }

        $extension = $image->getClientOriginalExtension() ?: 'png';
        $fileName = now()->format('YmdHis') . '-' . Str::random(12) . '.' . $extension;
        $destination = public_path(trim($dir, '/'));

        if (! is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $image->move($destination, $fileName);

        return $fileName;
    }

    public static function update(string $dir, ?string $oldImage, ?UploadedFile $image = null): ?string
    {
        if ($oldImage) {
            self::delete(self::pathFor($dir, $oldImage));
        }

        return self::upload($dir, $image);
    }

    public static function delete(?string $fullPath): void
    {
        if (! $fullPath) {
            return;
        }

        if (! self::isAbsolutePath($fullPath)) {
            $fullPath = public_path(ltrim($fullPath, '/'));
        }

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    protected static function pathFor(string $dir, string $fileName): string
    {
        return public_path(trim($dir, '/') . '/' . ltrim($fileName, '/'));
    }

    protected static function isAbsolutePath(string $path): bool
    {
        return preg_match('/^(?:[a-zA-Z]:)?[\\/]/', $path) === 1;
    }
}
