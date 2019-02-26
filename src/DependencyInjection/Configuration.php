<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('setono_sylius_criteo');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('account_id')
                    ->isRequired()
                    ->min(1)
                ->end()
                ->arrayNode('routes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('home')
                            ->defaultValue('sylius_shop_homepage')
                        ->end()
                        ->scalarNode('cart')
                            ->defaultValue('sylius_shop_cart_summary')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
