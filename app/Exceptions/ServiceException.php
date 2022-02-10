<?php

namespace App\Exceptions;

use Exception;

class ServiceException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        \Log::error('New error in service check this - ' . $exception);
    }
}
