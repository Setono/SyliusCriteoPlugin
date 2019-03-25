<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Context;

use Setono\SyliusCriteoPlugin\Model\AccountInterface;

interface AccountContextInterface
{
    /**
     * Returns the account enabled for the active channel
     *
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface;
}
