<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $configuration = $menu->getChild('configuration');

        if (null !== $configuration) {
            $this->addChild($configuration);
        } else {
            $this->addChild($menu->getFirstChild());
        }
    }

    private function addChild(ItemInterface $item): void
    {
        $item
            ->addChild('criteo', [
                'route' => 'setono_sylius_criteo_admin_account_index',
            ])
            ->setLabel('setono_sylius_criteo.ui.criteo')
            ->setLabelAttribute('icon', 'sync');
    }
}
