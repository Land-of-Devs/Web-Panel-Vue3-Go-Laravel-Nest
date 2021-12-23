<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class FileUploader
{
    public static function store($file, $name, $dir)
    {
        if ($file) {
            $name  = $name . '.' . $file->getClientOriginalExtension();
            $path = '/app_data/'.$dir.'/';
            $file->move($path, $name);
            return $name;
        }
    }

    public static function update($newFile, $name, $dir, $oldFile)
    {
        self::delete($oldFile, $dir);
        return self::store($newFile, $name, $dir);
    }

    public static function delete($name, $dir)
    {
        $path = '/app_data/'.$dir.'/'.$name;
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
