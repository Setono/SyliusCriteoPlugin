<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\ScriptTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class HomeSubscriber extends TagSubscriber
{
    /**
     * @var string
     */
    private $homeRoute;

    public function __construct(TagBagInterface $tagBag, string $homeRoute)
    {
        parent::__construct($tagBag);

        $this->homeRoute = $homeRoute;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'add',
            ],
        ];
    }

    public function add(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        // Only add on 'real' page loads, not AJAX requests like add to cart
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if ($request->attributes->get('_route') !== $this->homeRoute) {
            return;
        }

        $this->tagBag->add(new ScriptTag('window.criteo_q.push({ event: "viewHome"});', Tags::TAG_HOME), TagBagInterface::SECTION_BODY_END);
    }
}
