<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Context;

use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Setono\SyliusCriteoPlugin\Repository\AccountRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class AccountContext implements AccountContextInterface
{
    private ChannelContextInterface $channelContext;

    private AccountRepositoryInterface $accountRepository;

    public function __construct(ChannelContextInterface $channelContext, AccountRepositoryInterface $accountRepository)
    {
        $this->channelContext = $channelContext;
        $this->accountRepository = $accountRepository;
    }

    public function getAccount(): ?AccountInterface
    {
        return $this->accountRepository->findOneByChannel($this->channelContext->getChannel());
    }
}
