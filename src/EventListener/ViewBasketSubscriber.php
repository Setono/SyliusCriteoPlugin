<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\Tag\TemplateTag;
use Setono\TagBag\TagBagInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ViewBasketSubscriber extends RouteTagSubscriber
{
    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        string $route,
        CartContextInterface $cartContext,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        parent::__construct($tagBag, $accountContext, $route, $requestStack, $firewallMap);

        $this->cartContext = $cartContext;
    }

    public function add(RequestEvent $event): void
    {
        if (!$this->guardRoute($event)) {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $cart = $this->cartContext->getCart();

        $tag = new TemplateTag('@SetonoSyliusCriteoPlugin/Tag/view_basket.js.twig', ['cart' => $cart]);
        $tag->setSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->addTag($tag);
    }
}
