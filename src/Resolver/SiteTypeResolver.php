<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

use Setono\SyliusCriteoPlugin\DeviceDetector\DeviceDetectorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SiteTypeResolver implements SiteTypeResolverInterface
{
    private const SESSION_KEY = 'setono_sylius_criteo_site_type';

    private DeviceDetectorInterface $deviceDetector;

    private SessionInterface $session;

    public function __construct(DeviceDetectorInterface $deviceDetector, SessionInterface $session)
    {
        $this->deviceDetector = $deviceDetector;
        $this->session = $session;
    }

    public function resolve(): string
    {
        if ($this->session->has(self::SESSION_KEY)) {
            return $this->session->get(self::SESSION_KEY);
        }

        $siteType = $this->getDeviceLetter();
        $this->session->set(self::SESSION_KEY, $siteType);

        return $siteType;
    }

    private function getDeviceLetter(): string
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
