<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Exception\MissingAccount;
use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
     * @var AccountInterface|null
     */
    private $account;

    public function __construct(TagBagInterface $tagBag, AccountContextInterface $accountContext)
    {
        $this->tagBag = $tagBag;
        $this->accountContext = $accountContext;
    }

    /**
     * Returns true if an account was found for current account context
     *
     * @return bool
     */
    protected function hasAccount(): bool
    {
        if (null === $this->account) {
            $this->account = $this->accountContext->getAccount();
        }

        return null !== $this->account;
    }

    /**
     * Returns the account from account context
     * Throws exception if no account was found
     *
     * @return AccountInterface
     */
    protected function getAccount(): AccountInterface
    {
        if (null === $this->account) {
            $this->account = $this->accountContext->getAccount();
            if (null === $this->account) {
                throw new MissingAccount();
            }
        }

        return $this->account;
    }
}
