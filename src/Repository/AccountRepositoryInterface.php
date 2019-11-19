<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Repository;

use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns the account that is enabled on the given channel or null if no account is enabled for the given channel
     */
    public function findOneByChannel(ChannelInterface $channel): ?AccountInterface;
}
