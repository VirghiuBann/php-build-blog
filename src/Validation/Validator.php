<?php
namespace App\Validation;

use Valitron\Validator as ValidatorValitron;

class Validator extends ValidatorValitron
{
    protected function checkAndSetLabel($field, $message, $params)
    {
        return str_replace('{field}', '', $message);
    }
}
