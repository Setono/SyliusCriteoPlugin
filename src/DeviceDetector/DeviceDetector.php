<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DeviceDetector;

use DeviceDetector\DeviceDetector as BaseDeviceDetector;
use Symfony\Component\HttpFoundation\RequestStack;

final class DeviceDetector implements DeviceDetectorInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var BaseDeviceDetector */
    private $deviceDetector;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function isMobile(): bool
    {
        return $this->check('isMobile');
    }

    public function isTablet(): bool
    {
        return $this->check('isTablet');
    }

    private function check(string $checkMethod): bool
    {
        $deviceDetector = $this->getDeviceDetector();

        return $deviceDetector->{$checkMethod}();
    }

    private function getDeviceDetector(): BaseDeviceDetector
    {
        if (null === $this->deviceDetector) {
            $ua = '';

            $currentRequest = $this->requestStack->getCurrentRequest();
            if (null !== $currentRequest) {
                $ua = $currentRequest->headers->get('User-Agent');
                if (is_array($ua)) {
                    $ua = $ua[0];
                }
            }

            $this->deviceDetector = new BaseDeviceDetector($ua);
        }

        return $this->deviceDetector;
    }
}
