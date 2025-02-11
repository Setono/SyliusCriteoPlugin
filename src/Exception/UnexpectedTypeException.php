<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Exception;

use function get_class;
use function gettype;
use function is_object;

final class UnexpectedTypeException extends \InvalidArgumentException
{
    /**
     * @param mixed $value
     */
    public function __construct($value, string $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type %s, %s given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }
}
