<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\ProductIdResolverInterface;
use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\TagInterface;
use Setono\TagBagBundle\Tag\TwigTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\FirewallMapInterface;

final class ViewListSubscriber extends TagSubscriber
{
    /**
     * @var ProductIdResolverInterface
     */
    private $productIdResolver;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        ProductIdResolverInterface $productIdResolver,
        RequestStack $requestStack = null,
        FirewallMapInterface $firewallMap = null
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

        $this->tagBag->add(new TwigTag(
            '@SetonoSyliusCriteoPlugin/Tag/view_list.js.twig',
            TagInterface::TYPE_SCRIPT,
            Tags::TAG_VIEW_LIST,
            ['products' => $productIds]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
