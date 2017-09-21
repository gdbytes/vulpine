<?php

namespace Vulpine\Exceptions;

class WhmcsException extends VulpineException
{
    /**
     * Exception status code.
     *
     * @var int
     */
    protected $statusCode = 404;

    /**
     * @var bool
     */
    protected $shouldReport = true;
}