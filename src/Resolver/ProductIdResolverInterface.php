<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

use Sylius\Component\Product\Model\ProductInterface;

interface ProductIdResolverInterface
{
    /**
     * Returns the property that you consider the id of a product
     *
     * @param ProductInterface $product
     *
     * @return string
     */
    public function resolve(ProductInterface $product): string;
}
