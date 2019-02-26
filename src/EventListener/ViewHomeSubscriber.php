<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\ScriptTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

final class ViewHomeSubscriber extends RouteTagSubscriber
{
    public function add(GetResponseEvent $event): void
    {
        if (!$this->guardRoute($event)) {
            return;
        }

        $this->tagBag->add(new ScriptTag('window.criteo_q.push({ event: "viewHome"});', Tags::TAG_VIEW_HOME), TagBagInterface::SECTION_BODY_END);
    }
}
