<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\EventListener;

use Setono\SyliusCriteoPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\TagInterface;
use Setono\TagBagBundle\Tag\TwigTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
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

        if (!$this->hasAccount()) {
            return;
        }

        $this->tagBag->add(new TwigTag(
            '@SetonoSyliusCriteoPlugin/Tag/track_transaction.js.twig',
            TagInterface::TYPE_SCRIPT,
            Tags::TAG_TRACK_TRANSACTION,
            ['order' => $order]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
