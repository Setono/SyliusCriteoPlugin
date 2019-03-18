<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Context;

use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Setono\SyliusCriteoPlugin\Repository\AccountRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class AccountContext implements AccountContextInterface
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    public function __construct(ChannelContextInterface $channelContext, AccountRepositoryInterface $accountRepository)
    {
        $this->channelContext = $channelContext;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Returns the account enabled for the active channel
     *
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface
    {
        return $this->accountRepository->findOneByChannel($this->channelContext->getChannel());
    }
}
