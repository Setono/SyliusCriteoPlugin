<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Context\AccountContextInterface;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class RouteTagSubscriber extends TagSubscriber
{
    private $route;

    public function __construct(TagBagInterface $tagBag, AccountContextInterface $accountContext, string $route)
    {
        parent::__construct($tagBag, $accountContext);

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

    abstract public function add(GetResponseEvent $event): void;

    protected function guardRoute(GetResponseEvent $event): bool
    {
        if (!$event->isMasterRequest()) {
            return false;
        }

        $request = $event->getRequest();

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
