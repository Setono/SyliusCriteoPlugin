<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Model;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Account implements AccountInterface
{
    use ToggleableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannel(ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
}
