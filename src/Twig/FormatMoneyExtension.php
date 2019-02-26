<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class FormatMoneyExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('setono_sylius_criteo_format_money', [$this, 'formatMoney']),
        ];
    }

    public function formatMoney(int $money): float
    {
        return round($money / 100, 2);
    }
}
