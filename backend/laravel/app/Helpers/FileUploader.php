<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use File;

class FileUploader
{

    public static function name($title)
    {
        return Str::slug(substr($title, 0, 15)).'-'.time();
    }

	public static function store( $field, $file, $title, $loc)
  	{
        //dd($file);
        $request = new Request();
        if ($file){
            $name  = self::name($title);
            $name  = $name . '.' . $file->getClientOriginalExtension();
        
            $file->move($loc, $name);
            return asset($loc.'/'.$name);
        }

  	}

  	public static function update($field, $file, $title, $loc, $old)
  	{
        # remove old file
  		  self::delete($old);
        # store new file
        return self::store($field, $file, $title, $loc);
	    	
  	}

  	public static function delete($file)
  	{
  		if (File::exists($file)) {
      		File::delete($file);
    	}
  	}
}