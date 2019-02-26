<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusCriteoExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_criteo.account_id', $config['account_id']);
        $container->setParameter('setono_sylius_criteo.routes.home', $config['routes']['home']);
        $container->setParameter('setono_sylius_criteo.routes.cart', $config['routes']['cart']);

        $loader->load('services.xml');
    }
}
