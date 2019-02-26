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
    public function invalidIfRequiredValueIsNotProvided(): void
    {
        $this->assertConfigurationIsInvalid(
            [[]],
            'The child node "account_id" at path "setono_sylius_criteo" must be configured.'
        );
    }

    /**
     * @test
     */
    public function configurationIsValid(): void
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'account_id' => 123,
                    'routes' => [
                        'home' => 'test'
                    ]
                ]
            ]
        );
    }
}
