<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DeviceDetector;

/**
 * @todo this should use the https://github.com/matomo-org/device-detector library
 * but I am waiting for this issue to be answered https://github.com/CrossKnowledge/DeviceDetectBundle/issues/2
 */
interface DeviceDetectorInterface
{
    /**
     * Returns true if the users device is a mobile
     */
    public function isMobile(): bool;

    /**
     * Returns true if the users device is a tablet
     */
    public function isTablet(): bool;
}
