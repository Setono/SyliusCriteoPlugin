<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

use Sylius\Component\Product\Model\ProductInterface;

class ProductIdResolver implements ProductIdResolverInterface
{
    public function resolve(ProductInterface $product): string
    {
        return (string) $product->getCode();
    }
}
