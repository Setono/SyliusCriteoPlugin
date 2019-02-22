<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

interface SiteTypeResolverInterface
{
    /**
     * Returns 'm' for mobile, 't' for tablet, 'd' for desktop
     *
     * @return string
     */
    public function resolve(): string;
}
