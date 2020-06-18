<?php

namespace App;

use \PDO;

class Connection
{
  public static function getPDO(): PDO
  {
    return new PDO('mysql:dbname=blog;host=mysql', 'laradock', 'laradock', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
  }
}
