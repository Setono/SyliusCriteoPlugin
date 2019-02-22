<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

use Setono\SyliusCriteoPlugin\DeviceDetector\DeviceDetectorInterface;

final class SiteTypeResolver implements SiteTypeResolverInterface
{
    /**
     * @var DeviceDetectorInterface
     */
    private $deviceDetector;

    public function __construct(DeviceDetectorInterface $deviceDetector)
    {
        $this->deviceDetector = $deviceDetector;
    }

    public function resolve(): string
    {
        if ($this->deviceDetector->isMobile()) {
            return 'm';
        }

        if ($this->deviceDetector->isTablet()) {
            return 't';
        }

        return 'd';
    }
}
