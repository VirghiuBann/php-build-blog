<?php

namespace App\Table\Exception;

use \Exception;

class NotFoundException extends Exception
{

    public function __construct(string $table, int $id)
    {
        $this->message = "This #id '$id' not correspond the list '$table'";
    }
}
