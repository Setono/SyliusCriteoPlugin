<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\TagBag\TagBagInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class RouteTagSubscriber extends TagSubscriber
{
    /** @var string */
    private $route;

    public function __construct(
        TagBagInterface $tagBag,
        AccountContextInterface $accountContext,
        string $route,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        parent::__construct($tagBag, $accountContext, $requestStack, $firewallMap);

        $this->route = $route;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'add',
            ],
        ];
    }

    abstract public function add(RequestEvent $event): void;

    protected function guardRoute(RequestEvent $event): bool
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || !$this->isShopContext($request)) {
            return false;
        }

        // Only add on 'real' page loads, not AJAX requests like add to cart
        if ($request->isXmlHttpRequest()) {
            return false;
        }

        if ($request->attributes->get('_route') !== $this->route) {
            return false;
        }

        return true;
    }
}
