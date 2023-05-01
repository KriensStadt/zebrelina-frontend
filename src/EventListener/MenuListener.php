<?php

declare(strict_types=1);

namespace App\EventListener;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsTaggedItem(index: 'knp_menu.voter')]
#[AsEventListener(event: 'kernel.controller', method: 'onController', priority: -1)]
class MenuListener implements VoterInterface
{
    private array $active = [];

    public function matchItem(ItemInterface $item): bool|null
    {
        if (\count($this->active) <= 0) {
            return null;
        }

        foreach ($this->active as $active) {
            /** @var string $menu */
            $menu = $active['menu'];

            if ($item->getName() !== $menu) {
                continue;
            }

            return true;
        }

        return null;
    }

    public function onController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        /** @var array|string $menusActive */
        $menusActive = $request->attributes->get('_menu');

        if (!$menusActive) {
            return;
        }

        if (is_string($menusActive)) {
            $menusActive = [$menusActive];
        }

        foreach ($menusActive as $menuActive) {
            $this->active[] = [
                'menu' => $menuActive,
            ];
        }
    }
}
