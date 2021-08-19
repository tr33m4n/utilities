<?php

declare(strict_types=1);

namespace tr33m4n\Utilities\Tests\Config;

use PHPUnit\Framework\TestCase;
use tr33m4n\Utilities\Config\ConfigCollection;
use tr33m4n\Utilities\Exception\ConfigException;

final class ConfigCollectionTest extends TestCase
{
    /**
     * @var \tr33m4n\Utilities\Config\ConfigCollection
     */
    private $configCollection;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->configCollection = new ConfigCollection();
    }

    /**
     * Test that the config collection can be created statically
     *
     * @test
     * @return void
     */
    public function assertDataCollectionCanBeCreatedStatically(): void
    {
        $this->assertEquals(ConfigCollection::from(), $this->configCollection);
    }

    /**
     * Assert set returns expected value
     *
     * @test
     * @return void
     */
    public function assertSetReturnsExpectedValue(): void
    {
        $this->assertEquals($this->configCollection->set('foo', 'bar'), $this->configCollection);
    }

    /**
     * Assert get returns expected value
     *
     * @test
     * @dataProvider getDataProvider
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function assertGetReturnsExpectedValue(string $key, $value): void
    {
        $this->configCollection->set($key, $value);
        $this->assertEquals($this->configCollection->get($key), $value);
    }

    /**
     * Assert exception is thrown when "getting" a value that does not exist
     *
     * @test
     */
    public function assertGetThrowsExceptionWhenKeyIsMissing(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config key "missing" does not exist');

        $this->configCollection->get('missing');
    }

    /**
     * Assert has returns expected value
     *
     * @test
     * @dataProvider hasDataProvider
     * @param mixed $key
     * @param mixed $value
     * @param bool  $expected
     * @return void
     */
    public function assertHasReturnsExpectedValue($key, $value, bool $expected): void
    {
        $this->configCollection->set($key, $value);
        $this->assertEquals($this->configCollection->has($key), $expected);
    }

    /**
     * Assert setAll returns expected value
     *
     * @test
     * @return void
     */
    public function assertSetAllReturnsExpectedValue(): void
    {
        $this->assertEquals($this->configCollection->setAll(['foo' => 'bar']), $this->configCollection);
    }

    /**
     * Assert getAll returns expected value
     *
     * @test
     * @return void
     */
    public function assertGetAllReturnsExpectedValue(): void
    {
        $testData = $this->getDataProviderAsKeyValuePairs();

        $this->configCollection->setAll($testData);
        $this->assertEquals($this->configCollection->getAll(), $testData);
    }

    /**
     * Assert that the data collection can be iterated
     * @test
     * @return void
     */
    public function assertDataCollectionCanBeIterated(): void
    {
        $testData = $this->getDataProviderAsKeyValuePairs();
        $this->configCollection->setAll($testData);

        $arrayToCompare = [];
        foreach ($this->configCollection as $key => $value) {
            $arrayToCompare[$key] = $value;
        }

        $this->assertEquals($arrayToCompare, $testData);
    }

    /**
     * Get data provider as key value pairs
     *
     * @return array
     */
    public function getDataProviderAsKeyValuePairs(): array
    {
        $testData = [];
        foreach ($this->getDataProvider() as $testArray) {
            $testData[$testArray[0]] = $testArray[1];
        }

        return $testData;
    }

    /**
     * "has" data provider
     *
     * @return array
     */
    public function hasDataProvider(): array
    {
        return [
            ['test', 'test', true],
            ['another_test', [], true]
        ];
    }

    /**
     * "get" data provider
     *
     * @return array
     */
    public function getDataProvider(): array
    {
        return [
            ['testing', 'helloworld'],
            ['another', []],
            ['something', (object) []],
            ['hello', 1]
        ];
    }
}
