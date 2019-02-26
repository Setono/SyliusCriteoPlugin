<?php

namespace spec\Setono\SyliusCriteoPlugin\DeviceDetector;

use Setono\SyliusCriteoPlugin\DeviceDetector\DeviceDetector;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RequestStack;

class DeviceDetectorSpec extends ObjectBehavior
{
    public function let(RequestStack $requestStack): void
    {
        $this->beConstructedWith($requestStack);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DeviceDetector::class);
    }

    public function is_mobile_returns_bool(): void
    {
        $this->isMobile()->shouldBeBool();
    }

    public function is_tablet_returns_bool(): void
    {
        $this->isTablet()->shouldBeBool();
    }
}
