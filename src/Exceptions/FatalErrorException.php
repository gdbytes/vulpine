<?php

namespace Vulpine\Exceptions;

class FatalErrorException extends VulpineException
{
    /**
     * Exception status code.
     *
     * @var int
     */
    protected $statusCode = 500;

    /**
     * @var bool
     */
    protected $shouldReport = true;
}