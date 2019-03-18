<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\SyliusCriteoPlugin\Resolver\ProductIdResolverInterface;
use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\ScriptTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Product\Model\ProductInterface;

final class ViewProductSubscriber extends TagSubscriber
{
    /**
     * @var ProductIdResolverInterface
     */
    private $productIdResolver;

    public function __construct(TagBagInterface $tagBag, AccountContextInterface $account, ProductIdResolverInterface $productIdResolver)
    {
        parent::__construct($tagBag, $account);

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
        if ($this->accountContext->getAccount() === null) {
            return;
        }

        $product = $event->getSubject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        $this->tagBag->add(new ScriptTag(
            sprintf('window.criteo_q.push({ event: "viewItem", item: "%s" });', $this->productIdResolver->resolve($product)),
            Tags::TAG_VIEW_PRODUCT
        ), TagBagInterface::SECTION_BODY_END);
    }
}
