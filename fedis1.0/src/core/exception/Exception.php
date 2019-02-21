<?php
/*
 * Description:
 * Author: feipeixuan
 */
namespace org\fedis\exception;

use Throwable;

class ConnectionException extends \Exception{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
