<?php

namespace tr33m4n\Utilities\Tests;

use PHPUnit\Framework\TestCase;
use tr33m4n\Utilities\Registry;
use tr33m4n\Utilities\Exception\RegistryException;

/**
 * RegistryTest class
 */
final class RegistryTest extends TestCase
{
    /**
     * Assert has returns expected value
     *
     * @test
     * @dataProvider hasDataProvider
     * @throws RegistryException
     * @param mixed $key
     * @param mixed $value
     * @param bool $expected
     * @return void
     */
    public function assertHasReturnsExpectedValue($key, $value, $expected)
    {
        Registry::set($key, $value);
        $this->assertEquals(Registry::has($key), $expected);
    }

    /**
     * Assert set throws exception if key exists
     *
     * @test
     * @throws RegistryException
     * @return void
     */
    public function assertSetThrowsAnExceptionIfKeyExists()
    {
        $this->expectException(RegistryException::class);
        Registry::set('throw_exception', true);
        Registry::set('throw_exception', true);
    }

    /**
     * Assert get returns expected value
     *
     * @test
     * @dataProvider getDataProvider
     * @throws RegistryException
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function assertGetReturnsExpectedValue($key, $value)
    {
        Registry::set($key, $value);
        $this->assertEquals(Registry::get($key), $value);
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
