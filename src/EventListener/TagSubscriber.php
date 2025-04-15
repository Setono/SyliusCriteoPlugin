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

    private AccountInterface $account;

    private ?bool $hasAccount = null;

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
     * Returns true if an account was found for current account context
     *
     * @psalm-assert-if-true AccountInterface $this->account
     */
    protected function hasAccount(): bool
    {
        if (null === $this->hasAccount) {
            $account = $this->accountContext->getAccount();
            if (null !== $account) {
                $this->account = $account;
                $this->hasAccount = true;
            } else {
                $this->hasAccount = false;
            }
        }

        return $this->hasAccount;
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
