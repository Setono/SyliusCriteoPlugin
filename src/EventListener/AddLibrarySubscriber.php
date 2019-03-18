<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\SiteTypeResolver;
use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\TagInterface;
use Setono\TagBagBundle\Tag\TwigTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddLibrarySubscriber extends TagSubscriber
{
    /**
     * @var SiteTypeResolver
     */
    private $siteTypeResolver;

    public function __construct(TagBagInterface $tagBag, AccountContextInterface $accountContext, SiteTypeResolver $siteTypeResolver)
    {
        parent::__construct($tagBag, $accountContext);

        $this->siteTypeResolver = $siteTypeResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'add',
            ],
        ];
    }

    public function add(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // Only add the library on 'real' page loads, not AJAX requests like add to cart
        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $account = $this->accountContext->getAccount();
        if ($account === null) {
            return;
        }

        $this->tagBag->add(new TwigTag(
            '@SetonoSyliusCriteoPlugin/Tag/library.html.twig',
            TagInterface::TYPE_HTML,
            Tags::TAG_LIBRARY
        ), TagBagInterface::SECTION_HEAD);

        $this->tagBag->add(new TwigTag(
            '@SetonoSyliusCriteoPlugin/Tag/default_events.js.twig',
            TagInterface::TYPE_SCRIPT,
            Tags::TAG_DEFAULT_EVENTS,
            [
                'account_id' => $account->getAccountId(),
                'site_type' => $this->siteTypeResolver->resolve(),
            ]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
