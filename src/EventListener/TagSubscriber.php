<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
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

    public function __construct(TagBagInterface $tagBag, AccountContextInterface $accountContext)
    {
        $this->tagBag = $tagBag;
        $this->accountContext = $accountContext;
    }
}
