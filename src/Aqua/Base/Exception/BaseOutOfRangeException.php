<?php
declare(strict_types=1);

namespace Aqua\Base\Exception;

class BaseOutOfRangeException extends \OutOfRangeException
{
    /**
     * Exception thrown when adding an element to a full container.
     *
     * @param string $message
     * @param integer $code
     * @param \OutOfRangeException|null $previous
     */
    public function __construct(string $message, int $code = 0, \OutOfRangeException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}