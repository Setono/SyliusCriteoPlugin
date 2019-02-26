<?php

namespace spec\Setono\SyliusCriteoPlugin\Resolver;

use Setono\SyliusCriteoPlugin\Resolver\ProductIdResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductInterface;

class ProductIdResolverSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductIdResolver::class);
    }

    public function it_resolves(ProductInterface $product): void
    {
        $product->getCode()->willReturn('code');

        $this->resolve($product)->shouldReturn('code');
    }
}
