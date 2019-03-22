<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Exception;

final class MissingAccount extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('There was not found any Criteo account.');
    }
}
