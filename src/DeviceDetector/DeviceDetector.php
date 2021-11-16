<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DeviceDetector;

use DeviceDetector\DeviceDetector as BaseDeviceDetector;
use Symfony\Component\HttpFoundation\RequestStack;

final class DeviceDetector implements DeviceDetectorInterface
{
    private RequestStack $requestStack;

    private BaseDeviceDetector $deviceDetector;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function isMobile(): bool
    {
        return $this->getDeviceDetector()->isMobile();
    }

    public function isTablet(): bool
    {
        return $this->getDeviceDetector()->isTablet();
    }

    private function getDeviceDetector(): BaseDeviceDetector
    {
        if (null === $this->deviceDetector) {
            $ua = '';

            $currentRequest = $this->requestStack->getCurrentRequest();
            if (null !== $currentRequest) {
                $ua = $currentRequest->headers->get('User-Agent');
            }

            $this->deviceDetector = new BaseDeviceDetector((string) $ua);
        }

        return $this->deviceDetector;
    }
}
