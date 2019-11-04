<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\TagInterface;
use Setono\TagBagBundle\Tag\TwigTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\FirewallMapInterface;

final class ViewBasketSubscriber extends RouteTagSubscriber
{
    /**
     * @var CartContextInterface
     */
    private $cartContext;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        string $route,
        CartContextInterface $cartContext,
        RequestStack $requestStack = null,
        FirewallMapInterface $firewallMap = null
    ) {
        parent::__construct($tagBag, $accountContext, $route, $requestStack, $firewallMap);

        $this->cartContext = $cartContext;
    }

    public function add(GetResponseEvent $event): void
    {
        if (!$this->guardRoute($event)) {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $cart = $this->cartContext->getCart();

        $this->tagBag->add(new TwigTag(
            '@SetonoSyliusCriteoPlugin/Tag/view_basket.js.twig',
            TagInterface::TYPE_SCRIPT,
            Tags::TAG_VIEW_BASKET,
            ['cart' => $cart]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
