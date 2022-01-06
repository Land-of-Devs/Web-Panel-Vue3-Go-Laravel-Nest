<?php

namespace App\Traits;

trait RepositoryUtilsTrait
{
  protected static function cleanArray(array $conds) {
    return array_filter($conds, fn($value) => !is_null($value) && $value !== '');
  }

  protected static function cleanWhere(array $params){
    $arrParent = array();
    foreach($params as $arr ){
        if(!is_null($arr["value"]) && $arr["value"] !== ''){
            array_push($arrParent, [$arr["key"], $arr["exp"], $arr["value"]]);
        }
    }
    return $arrParent;
  }
}