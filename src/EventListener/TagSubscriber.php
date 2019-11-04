<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Exception\MissingAccount;
use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class TagSubscriber implements EventSubscriberInterface
{
    /**
     * @var TagBagInterface
     */
    protected $tagBag;

    /**
     * @var AccountContextInterface
     */
    protected $accountContext;

    /**
     * @var AccountInterface
     */
    private $account;

    /**
     * @var bool
     */
    private $hasAccount;

    /**
     * @var RequestStack|null
     */
    private $requestStack;

    /**
     * @var string|null
     */
    private $shopContextPattern;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        RequestStack $requestStack = null,
        string $shopContextPattern = null
    ) {
        $this->tagBag = $tagBag;
        $this->accountContext = $accountContext;
        $this->requestStack = $requestStack;
        $this->shopContextPattern = $shopContextPattern;
    }

    /**
     * Returns true if an account was found for current account context
     *
     * @return bool
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
     *
     * @return AccountInterface
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
        if (null === $this->shopContextPattern) {
            return true;
        }

        if (null === $request) {
            if (null === $this->requestStack) {
                return true;
            }

            $request = $this->requestStack->getCurrentRequest();
            if (null === $request) {
                return true;
            }
        }

        return preg_match(
            '#' . str_replace('#', '\#', $this->shopContextPattern) . '#',
            $request->getPathInfo()
        ) === 1;
    }
}
