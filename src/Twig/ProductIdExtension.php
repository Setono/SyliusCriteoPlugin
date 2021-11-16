<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Twig;

use Setono\SyliusCriteoPlugin\Exception\UnexpectedTypeException;
use Setono\SyliusCriteoPlugin\Resolver\ProductIdResolverInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ProductIdExtension extends AbstractExtension
{
    private ProductIdResolverInterface $productIdResolver;

    public function __construct(ProductIdResolverInterface $productIdResolver)
    {
        $this->productIdResolver = $productIdResolver;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('setono_sylius_criteo_product_id', [$this, 'productId']),
        ];
    }

    /**
     * @param ProductInterface|mixed|null $product
     */
    public function productId($product): string
    {
        if (null === $product) {
            return '';
        }

        if (!$product instanceof ProductInterface) {
            throw new UnexpectedTypeException($product, ProductInterface::class);
        }

        return $this->productIdResolver->resolve($product);
    }
}
