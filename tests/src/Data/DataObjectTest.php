<?php

namespace tr33m4n\Utilities\Tests\Data;

use PHPUnit\Framework\TestCase;
use tr33m4n\Utilities\Data\DataObject;

/**
 * DataObjectTest class
 */
final class DataObjectTest extends TestCase
{
    /**
     * @var \tr33m4n\Utilities\Data\DataObject
     */
    private $dataObject;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->dataObject = new DataObject();
    }

    /**
     * Assert set returns expected value
     *
     * @test
     * @return void
     */
    public function assertSetReturnsExpectedValue() : void
    {
        $this->assertEquals($this->dataObject->set('foo', 'bar'), $this->dataObject);
    }

    /**
     * Assert get returns expected value
     *
     * @test
     * @dataProvider getDataProvider
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function assertGetReturnsExpectedValue($key, $value) : void
    {
        $this->dataObject->set($key, $value);
        $this->assertEquals($this->dataObject->get($key), $value);
    }

    /**
     * Assert has returns expected value
     *
     * @test
     * @dataProvider hasDataProvider
     * @param mixed $key
     * @param mixed $value
     * @param bool $expected
     * @return void
     */
    public function assertHasReturnsExpectedValue($key, $value, $expected) : void
    {
        $this->dataObject->set($key, $value);
        $this->assertEquals($this->dataObject->has($key), $expected);
    }

    /**
     * Assert setAll returns expected value
     *
     * @test
     * @return void
     */
    public function assertSetAllReturnsExpectedValue() : void
    {
        $this->assertEquals($this->dataObject->setAll(['foo' => 'bar']), $this->dataObject);
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

        $this->dataObject->setAll($testData);
        $this->assertEquals($this->dataObject->getAll(), $testData);
    }

    /**
     * Assert that the data object can be iterated
     *
     * @test
     * @return void
     */
    public function assertDataObjectCanBeIterated() : void
    {
        $testData = $this->getDataProviderAsKeyValuePairs();
        $this->dataObject->setAll($testData);

        $arrayToCompare = [];
        foreach ($this->dataObject as $key => $value) {
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
