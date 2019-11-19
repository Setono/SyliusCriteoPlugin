<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

use Sylius\Component\Product\Model\ProductInterface;

interface ProductIdResolverInterface
{
    /**
     * Returns the property that you consider the id of a product
     */
    public function resolve(ProductInterface $product): string;
}
