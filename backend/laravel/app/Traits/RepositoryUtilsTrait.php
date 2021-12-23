<?php

namespace App\Traits;

trait RepositoryUtilsTrait
{
  protected static function cleanArray(array $conds) {
    return array_filter($conds, fn($value) => !is_null($value) && $value !== '');
  }
}