<?php

namespace tr33m4n\Utilities\Tests\Data;

use PHPUnit\Framework\TestCase;
use tr33m4n\Utilities\Data\DataCollection;

/**
 * DataCollectionTest class
 */
final class DataCollectionTest extends TestCase
{
    /**
     * @var \tr33m4n\Utilities\Data\DataCollection
     */
    private $dataCollection;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->dataCollection = new DataCollection();
    }

    /**
     * Test that the data collection can be created statically
     *
     * @test
     * @return void
     */
    public function assertDataCollectionCanBeCreatedStatically() : void
    {
        $this->assertEquals(DataCollection::from(), $this->dataCollection);
    }

    /**
     * Assert set returns expected value
     *
     * @test
     * @return void
     */
    public function assertSetReturnsExpectedValue() : void
    {
        $this->assertEquals($this->dataCollection->set('foo', 'bar'), $this->dataCollection);
    }

    /**
     * Assert get returns expected value
     *
     * @test
     * @dataProvider getDataProvider
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function assertGetReturnsExpectedValue(string $key, $value) : void
    {
        $this->dataCollection->set($key, $value);
        $this->assertEquals($this->dataCollection->get($key), $value);
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
    public function assertHasReturnsExpectedValue($key, $value, bool $expected) : void
    {
        $this->dataCollection->set($key, $value);
        $this->assertEquals($this->dataCollection->has($key), $expected);
    }

    /**
     * Assert setAll returns expected value
     *
     * @test
     * @return void
     */
    public function assertSetAllReturnsExpectedValue() : void
    {
        $this->assertEquals($this->dataCollection->setAll(['foo' => 'bar']), $this->dataCollection);
    }

    /**
     * Assert getAll returns expected value
     *
     * @test
     * @return void
     */
    public function assertGetAllReturnsExpectedValue() : void
    {
        $testData = $this->getDataProviderAsKeyValuePairs();

        $this->dataCollection->setAll($testData);
        $this->assertEquals($this->dataCollection->getAll(), $testData);
    }

    /**
     * Assert that the data collection can be iterated
     *
     * @test
     * @return void
     */
    public function assertDataCollectionCanBeIterated() : void
    {
        $testData = $this->getDataProviderAsKeyValuePairs();
        $this->dataCollection->setAll($testData);

        $arrayToCompare = [];
        foreach ($this->dataCollection as $key => $value) {
            $arrayToCompare[$key] = $value;
        }

        $this->assertEquals($arrayToCompare, $testData);
    }

    /**
     * Get data provider as key value pairs
     *
     * @return array
     */
    public function getDataProviderAsKeyValuePairs() : array
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
    public function hasDataProvider() : array
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
    public function getDataProvider() : array
    {
        return [
            ['testing', 'helloworld'],
            ['another', []],
            ['something', (object) []],
            ['hello', 1]
        ];
    }
}
