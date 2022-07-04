<?php

namespace Rcdelfin\Inventory\Exceptions;

use Exception;

class InvalidProductException extends Exception
{
    public function report()
    {
        logger('Invalid Product');
    }
}
