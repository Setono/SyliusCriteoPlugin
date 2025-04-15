<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\ProductIdResolverInterface;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\Tag\TemplateTag;
use Setono\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;

final class ViewListSubscriber extends TagSubscriber
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
            'sylius.product.index' => 'add',
        ];
    }

    public function add(ResourceControllerEvent $event): void
    {
        /** @var mixed $subject */
        $subject = $event->getSubject();

        if (!$this->isShopContext()) {
            return;
        }

        if ($subject instanceof ResourceGridView) {
            /** @var mixed $products */
            $products = $subject->getData();
        } elseif (is_iterable($subject)) {
            $products = $subject;
        } else {
            return;
        }

        if (!is_iterable($products) || !$this->hasAccount()) {
            return;
        }

        $productIds = [];

        $i = 0;
        foreach ($products as $product) {
            if (!$product instanceof ProductInterface) {
                return;
            }

            if ($i >= 3) {
                break;
            }

            $productIds[] = $this->productIdResolver->resolve($product);

            ++$i;
        }

        $tag = TemplateTag::create('@SetonoSyliusCriteoPlugin/Tag/view_list.html.twig', ['products' => $productIds])
            ->withSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->add($tag);
    }
}
