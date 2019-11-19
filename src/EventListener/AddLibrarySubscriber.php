<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\SiteTypeResolver;
use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\TagInterface;
use Setono\TagBagBundle\Tag\TwigTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddLibrarySubscriber extends TagSubscriber
{
    /** @var SiteTypeResolver */
    private $siteTypeResolver;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        SiteTypeResolver $siteTypeResolver,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        parent::__construct($tagBag, $accountContext, $requestStack, $firewallMap);

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
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || !$this->isShopContext($request)) {
            return;
        }

        // Only add the library on 'real' page loads, not AJAX requests like add to cart
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if (!$this->hasAccount()) {
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
                'account_id' => $this->getAccount()->getAccountId(),
                'site_type' => $this->siteTypeResolver->resolve(),
            ]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
