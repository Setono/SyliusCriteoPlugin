<?php

namespace spec\Setono\SyliusCriteoPlugin\Resolver;

use Setono\SyliusCriteoPlugin\DeviceDetector\DeviceDetectorInterface;
use Setono\SyliusCriteoPlugin\Resolver\SiteTypeResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SiteTypeResolverSpec extends ObjectBehavior
{
    public function let(DeviceDetectorInterface $deviceDetector, SessionInterface $session): void
    {
        $session->has(Argument::type('string'))->willReturn(false);

        $this->beConstructedWith($deviceDetector, $session);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(SiteTypeResolver::class);
    }

    public function it_resolves_mobile(DeviceDetectorInterface $deviceDetector, SessionInterface $session): void
    {
        $deviceLetter = 'm';
        $deviceDetector->isMobile()->willReturn(true);
        $session->set(Argument::type('string'), $deviceLetter)->shouldBeCalled();

        $this->resolve()->shouldReturn($deviceLetter);
    }

    public function it_resolves_tablet(DeviceDetectorInterface $deviceDetector, SessionInterface $session): void
    {
        $deviceLetter = 't';
        $deviceDetector->isMobile()->willReturn(false);
        $deviceDetector->isTablet()->willReturn(true);
        $session->set(Argument::type('string'), $deviceLetter)->shouldBeCalled();

        $this->resolve()->shouldReturn($deviceLetter);
    }

    public function it_resolves_desktop(DeviceDetectorInterface $deviceDetector, SessionInterface $session): void
    {
        $deviceLetter = 'd';
        $deviceDetector->isMobile()->willReturn(false);
        $deviceDetector->isTablet()->willReturn(false);
        $session->set(Argument::type('string'), $deviceLetter)->shouldBeCalled();

        $this->resolve()->shouldReturn($deviceLetter);
    }
}
