<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Doctrine\ORM;

use Setono\SyliusCriteoPlugin\Model\AccountInterface;
use Setono\SyliusCriteoPlugin\Repository\AccountRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class AccountRepository extends EntityRepository implements AccountRepositoryInterface
{
    public function findOneByChannel(ChannelInterface $channel): ?AccountInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.enabled = true')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
