<?php

declare(strict_types=1);

namespace Setono\SyliusCriteoPlugin\Resolver;

use Setono\SyliusCriteoPlugin\DeviceDetector\DeviceDetectorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final class SiteTypeResolver implements SiteTypeResolverInterface
{
    private const SESSION_KEY = 'setono_sylius_criteo_site_type';

    public function __construct(
        private readonly DeviceDetectorInterface $deviceDetector,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function resolve(): string
    {
        $session = $this->requestStack->getCurrentRequest()?->getSession();
        Assert::notNull($session);

        if ($session->has(self::SESSION_KEY)) {
            /** @var mixed $siteType */
            $siteType = $session->get(self::SESSION_KEY);
            if (is_string($siteType)) {
                return $siteType;
            }
        }

        $siteType = $this->getDeviceLetter();
        $session->set(self::SESSION_KEY, $siteType);

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
