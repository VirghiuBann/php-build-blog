<?php

namespace App;

use Exception;

class URL
{
  public static function getInt(string $name, ?int $default = null): ?int
  {
    if (!isset($_GET[$name])) return $default;
    if ($_GET[$name] === '0') return 0;

    if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
      throw new Exception('This parameter is not valid number');
    }
    return (int) $_GET[$name];
  }

  public static function getPositiveInt(string $name, ?int $default = null): ?int
  {
    $param = self::getInt($name, $default);
    if ($param !== null && $param <= 0) {
      throw new Exception("This parameter $name is not positive number");
    }
    return $param;
  }
}
