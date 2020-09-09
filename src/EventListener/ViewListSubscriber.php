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
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;

final class ViewListSubscriber extends TagSubscriber
{
    /** @var ProductIdResolverInterface */
    private $productIdResolver;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        ProductIdResolverInterface $productIdResolver,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        parent::__construct($tagBag, $accountContext, $requestStack, $firewallMap);

        $this->productIdResolver = $productIdResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.index' => [
                'add',
            ],
        ];
    }

    public function add(ResourceControllerEvent $event): void
    {
        $subject = $event->getSubject();

        if (!$this->isShopContext()) {
            return;
        }

        if ($subject instanceof ResourceGridView) {
            $products = $subject->getData();
        } elseif (is_iterable($subject)) {
            $products = $subject;
        } else {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $productIds = [];

        $i = 0;
        foreach ($products as $product) {
            if ($i >= 3) {
                break;
            }

            $productIds[] = $this->productIdResolver->resolve($product);

            ++$i;
        }

        $tag = new TemplateTag('@SetonoSyliusCriteoPlugin/Tag/view_list.js.twig', ['products' => $productIds]);
        $tag->setSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->addTag($tag);
    }
}
