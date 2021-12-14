<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileUploader
{
	public static function store( $field, $file, $slug, $type, $loc)
  	{
        //dd($file);
        $request = new Request();
        if ($file){
            $name  = $slug . '.' . $file->getClientOriginalExtension();
        
            $file->move($loc, $name);
            return '/api/data/img/'.$type.'/'.$name;
        }

  	}

  	public static function update($field, $file, $slug, $type, $loc, $old)
  	{
        # remove old file
  		  self::delete($old);
        # store new file
        return self::store($field, $file, $slug, $type, $loc);
	    	
  	}

  	public static function delete($file)
  	{
  		if (File::exists($file)) {
      		File::delete($file);
    	}
  	}
}