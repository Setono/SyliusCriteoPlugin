<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\Tag\TemplateTag;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;

final class TrackTransactionSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_complete' => [
                'add',
            ],
        ];
    }

    public function add(ResourceControllerEvent $event): void
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface) {
            return;
        }

        if (!$this->isShopContext()) {
            return;
        }

        if (!$this->hasAccount()) {
            return;
        }

        $tag = new TemplateTag('@SetonoSyliusCriteoPlugin/Tag/track_transaction.js.twig', ['order' => $order]);
        $tag->setSection(TagInterface::SECTION_BODY_END);
        $this->tagBag->addTag($tag);
    }
}
