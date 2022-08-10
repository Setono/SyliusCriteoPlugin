<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ViewHomeSubscriber extends RouteTagSubscriber
{
    public function add(RequestEvent $event): void
    {
        if (!$this->guardRoute($event)) {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $tag = InlineScriptTag::create('window.criteo_q.push({ event: "viewHome"});')
            ->withSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->add($tag);
    }
}
