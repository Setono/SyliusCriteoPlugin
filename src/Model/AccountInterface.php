<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Model;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface AccountInterface extends ResourceInterface, ToggleableInterface
{
    public function getId(): ?int;

    public function getAccountId(): ?string;

    public function setAccountId(string $accountId): void;

    public function getChannel(): ?ChannelInterface;

    public function setChannel(ChannelInterface $channel): void;
}
