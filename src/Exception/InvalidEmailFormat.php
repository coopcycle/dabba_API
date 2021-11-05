<?php
namespace App\Exception;

final class InvalidEmailFormat extends DabbaException
{
    public function __construct($message = "", $code = 400, \Throwable $previous = null) {
        parent::__construct($message,$code,$previous);
    }
}