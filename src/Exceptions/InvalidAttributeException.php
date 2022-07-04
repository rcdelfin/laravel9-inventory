<?php

namespace Rcdelfin\Inventory\Exceptions;

use Exception;

class InvalidAttributeException extends Exception
{
    public function report()
    {
        logger('Invalid Attribute');
    }
}
