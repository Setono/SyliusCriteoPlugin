<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Exception\MissingAccount;
use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Setono\TagBag\TagBagInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class TagSubscriber implements EventSubscriberInterface
{
    protected TagBagInterface $tagBag;

    protected AccountContextInterface $accountContext;

    private AccountInterface|false|null $account = false;

    private RequestStack $requestStack;

    private FirewallMap $firewallMap;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        RequestStack $requestStack,
        FirewallMap $firewallMap,
    ) {
        $this->tagBag = $tagBag;
        $this->accountContext = $accountContext;
        $this->requestStack = $requestStack;
        $this->firewallMap = $firewallMap;
    }

    /**
     * Returns true if an account was found for the current account context
     *
     * @psalm-assert-if-true AccountInterface $this->account
     */
    protected function hasAccount(): bool
    {
        if (false === $this->account) {
            $this->account = $this->accountContext->getAccount();
        }

        return null !== $this->account;
    }

    /**
     * Returns the account from account context
     * Throws exception if no account was found
     */
    protected function getAccount(): AccountInterface
    {
        if ($this->hasAccount()) {
            return $this->account;
        }

        throw new MissingAccount();
    }

    protected function isShopContext(Request $request = null): bool
    {
        if (null === $request) {
            $request = $this->requestStack->getCurrentRequest();
            if (null === $request) {
                return true;
            }
        }

        $firewallConfig = $this->firewallMap->getFirewallConfig($request);
        if (null === $firewallConfig) {
            return true;
        }

        return $firewallConfig->getName() === 'shop';
    }
}
