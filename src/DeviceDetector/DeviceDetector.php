<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\DeviceDetector;

use DeviceDetector\DeviceDetector as BaseDeviceDetector;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class DeviceDetector implements DeviceDetectorInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var BaseDeviceDetector
     */
    private $deviceDetector;

    public function __construct(SessionInterface $session, RequestStack $requestStack)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
    }

    public function isMobile(): bool
    {
        $deviceDetector = $this->getDeviceDetector();

        return $this->check('setono_sylius_criteo_mobile', [$deviceDetector, 'isMobile']);
    }

    public function isTablet(): bool
    {
        $deviceDetector = $this->getDeviceDetector();

        return $this->check('setono_sylius_criteo_tablet', [$deviceDetector, 'isTablet']);
    }

    private function check(string $key, callable $result): bool
    {
        if ($this->session->has($key)) {
            return (bool) $this->session->get($key);
        }

        $res = $result();

        $this->session->set($key, $res);

        return $res;
    }

    private function getDeviceDetector(): BaseDeviceDetector
    {
        if (null === $this->deviceDetector) {
            $ua = '';

            $currentRequest = $this->requestStack->getCurrentRequest();
            if(null !== $currentRequest) {
                $ua = $currentRequest->headers->get('User-Agent');
                if(is_array($ua)) {
                    $ua = $ua[0];
                }
            }

            $this->deviceDetector = new BaseDeviceDetector($ua);
        }

        return $this->deviceDetector;
    }
}
