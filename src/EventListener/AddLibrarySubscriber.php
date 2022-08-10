<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\MainRequestTrait\MainRequestTrait;
use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\SiteTypeResolver;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\Tag\TemplateTag;
use Setono\TagBag\TagBagInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddLibrarySubscriber extends TagSubscriber
{
    use MainRequestTrait;

    private SiteTypeResolver $siteTypeResolver;

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

        if (!$this->isMainRequest($event) || !$this->isShopContext($request)) {
            return;
        }

        // Only add the library on 'real' page loads, not AJAX requests like add to cart
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $tag = TemplateTag::create('@SetonoSyliusCriteoPlugin/Tag/library.html.twig', [
            'account_id' => $this->getAccount()->getAccountId(),
        ])->withSection(TagInterface::SECTION_HEAD);
        $this->tagBag->add($tag);

        $tag = TemplateTag::create('@SetonoSyliusCriteoPlugin/Tag/default_events.html.twig', [
            'account_id' => $this->getAccount()->getAccountId(),
            'site_type' => $this->siteTypeResolver->resolve(),
        ])->withSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->add($tag);
    }
}
