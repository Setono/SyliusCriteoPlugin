<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusCriteoExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{driver: string, resources: array<string, mixed>, routes: array<string, string>} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        $container->setParameter('setono_sylius_criteo.routes.home', $config['routes']['home']);
        $container->setParameter('setono_sylius_criteo.routes.cart', $config['routes']['cart']);

        $this->registerResources('setono_sylius_criteo', $config['driver'], $config['resources'], $container);
    }
}
