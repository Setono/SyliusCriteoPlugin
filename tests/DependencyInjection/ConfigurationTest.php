<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCriteoPlugin\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Setono\SyliusCriteoPlugin\DependencyInjection\Configuration;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function configurationIsValid(): void
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'routes' => [
                        'home' => 'test',
                    ],
                ],
            ],
        );
    }
}
