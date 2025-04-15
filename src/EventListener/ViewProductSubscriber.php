<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\ProductIdResolverInterface;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;

final class ViewProductSubscriber extends TagSubscriber
{
    private ProductIdResolverInterface $productIdResolver;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        ProductIdResolverInterface $productIdResolver,
        RequestStack $requestStack,
        FirewallMap $firewallMap,
    ) {
        parent::__construct($tagBag, $accountContext, $requestStack, $firewallMap);

        $this->productIdResolver = $productIdResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => [
                'add',
            ],
        ];
    }

    public function add(ResourceControllerEvent $event): void
    {
        $product = $event->getSubject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        if (!$this->isShopContext()) {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $tag = InlineScriptTag::create(sprintf(
            'window.criteo_q.push({ event: "viewItem", item: "%s" });',
            $this->productIdResolver->resolve($product),
        ))->withSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->add($tag);
    }
}
