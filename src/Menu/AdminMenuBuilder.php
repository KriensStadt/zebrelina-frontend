<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(
    tags: [
        ['name' => 'knp_menu.menu_builder', 'method' => 'build', 'alias' => 'menu_admin']
    ]
)]
class AdminMenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory,
    ) {
    }

    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('admin.index', [
            'route' => 'admin.index',
        ]);

        $menu->addChild('admin.admin', [
            'route' => 'admin.admin.index',
        ]);

        $menu->addChild('admin.device', [
            'route' => 'admin.device.index',
        ]);

        $menu->addChild('admin.group', [
            'route' => 'admin.group.index',
        ]);

        $menu->addChild('admin.time_period', [
            'route' => 'admin.time_period.index',
        ]);

        return $menu;
    }
}
