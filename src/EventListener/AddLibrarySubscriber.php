<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\SiteTypeResolver;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\Tag\TwigTag;
use Setono\TagBag\TagBagInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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

    public function add(RequestEvent $event): void
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

        $tag = new TwigTag('@SetonoSyliusCriteoPlugin/Tag/library.html.twig');
        $tag->setSection(TagInterface::SECTION_HEAD);
        $this->tagBag->addTag($tag);

        $tag = new TwigTag('@SetonoSyliusCriteoPlugin/Tag/default_events.html.twig', [
            'account_id' => $this->getAccount()->getAccountId(),
            'site_type' => $this->siteTypeResolver->resolve(),
        ]);
        $tag->setSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->addTag($tag);
    }
}
