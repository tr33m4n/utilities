<?php

namespace tr33m4n\Utilities\Tests\Config;

use PHPUnit\Framework\TestCase;
use tr33m4n\Utilities\Config\ConfigCollection;
use tr33m4n\Utilities\Config\ConfigProvider;
use tr33m4n\Utilities\Config\Container;

/**
 * Class ConfigProviderTest
 *
 * @package tr33m4n\Utilities\Tests\Config
 */
final class ConfigProviderTest extends TestCase
{
    private const CONFIG_PATHS = [
        __DIR__ . '/../fixtures/config',
        __DIR__ . '/../fixtures/config_override' // Add path to test overriding config files
    ];

    /**
     * @var \tr33m4n\Utilities\Config\ConfigProvider
     */
    private $configProvider;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->configProvider = Container::setConfigProvider(new ConfigProvider(self::CONFIG_PATHS));
    }

    /**
     * Test that the config provider can be created statically
     *
     * @test
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    public function assertConfigProviderCanBeCreatedStatically(): void
    {
        $this->assertEquals(ConfigProvider::from(self::CONFIG_PATHS), $this->configProvider);
    }

    /**
     * Test that the config provider initialises correctly
     *
     * @test
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    public function assertConfigProviderInitialisesCorrectly(): void
    {
        $this->assertEquals($this->configProvider->getAll(), $this->expectedConfigStructure());
        $this->assertEquals(config()->getAll(), $this->expectedConfigStructure());
    }

    /**
     * Test that we can access a deeply nested config value
     *
     * @test
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    public function assertNestedValuesAreAccessible(): void
    {
        $this->assertEquals(
            'test1',
            $this->configProvider->get('test1')->get('test1_3')->get('test3')->get('test2')->get('test1')
        );
        $this->assertEquals(
            'test1',
            config('test1')->get('test1_3')->get('test3')->get('test2')->get('test1')
        );
    }

    /**
     * Expected config structure
     *
     * @return array
     */
    private function expectedConfigStructure(): array
    {
        return [
            'test1' => ConfigCollection::from([
                'test1_1' => 'test1',
                'test1_2' => 123,
                'test1_3' => ConfigCollection::from([
                    'test1' => 'test1',
                    'test2' => 123,
                    'test3' => ConfigCollection::from([
                        'test1' => 'test1',
                        'test2' => ConfigCollection::from([
                            'test1' => 'test1'
                        ]),
                        'test3' => ConfigCollection::from([
                            'test1' => 'test1'
                        ])
                    ])
                ])
            ]),
            'test2' => ConfigCollection::from([
                'override1' => 'override1',
                'override2' => 123,
                'override3' => ConfigCollection::from([
                    'override1' => 'override1',
                    'override2' => 123,
                    'override3' => ConfigCollection::from([
                        'override1' => 'override1',
                        'override2' => ConfigCollection::from([
                            'override1' => 'override1'
                        ]),
                        'override3' => ConfigCollection::from([
                            'override1' => 'override1'
                        ])
                    ])
                ])
            ]),
            'test3' => ConfigCollection::from([
                'test3_1' => 'test1',
                'test3_2' => 123,
                'test3_3' => ConfigCollection::from([
                    'test1' => 'test1',
                    'test2' => 123,
                    'test3' => ConfigCollection::from([
                        'test1' => 'test1',
                        'test2' => ConfigCollection::from([
                            'test1' => 'test1'
                        ]),
                        'test3' => ConfigCollection::from([
                            'test1' => 'test1'
                        ])
                    ])
                ])
            ])
        ];
    }
}
