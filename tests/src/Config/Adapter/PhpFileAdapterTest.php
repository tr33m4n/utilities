<?php

namespace tr33m4n\Utilities\Tests\Config\Adapter;

use PHPUnit\Framework\TestCase;
use tr33m4n\Utilities\Config\Adapter\PhpFileAdapter;
use tr33m4n\Utilities\Exception\AdapterException;

/**
 * Class PhpFileAdapterTest
 *
 * @package tr33m4n\Utilities\Tests\Config\Adapter
 */
class PhpFileAdapterTest extends TestCase
{
    /**
     * @var \tr33m4n\Utilities\Config\Adapter\PhpFileAdapter
     */
    private $phpFileAdapter;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->phpFileAdapter = new PhpFileAdapter();
    }

    /**
     * Test the adapter has the correct file extension
     *
     * @test
     * @return void
     */
    public function assertAdapterHasTheRightFileExtension() : void
    {
        $this->assertEquals('php', $this->phpFileAdapter::getFileExtension());
    }

    /**
     * Test the file validation returns the correct value
     *
     * @test
     * @dataProvider validationDataProvider
     * @param string $filePath
     * @param bool   $expected
     * @return void
     */
    public function assertValidationReturnsCorrectBoolean(string $filePath, bool $expected) : void
    {
        $this->assertEquals($this->phpFileAdapter->validate($filePath), $expected);
    }

    /**
     * Test that the adapter can read PHP files
     *
     * @test
     * @dataProvider readDataProvider
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string $filePath
     * @param array  $expected
     * @return void
     */
    public function assertAdapterCanReadPhpFile(string $filePath, array $expected) : void
    {
        $this->assertEquals($this->phpFileAdapter->read($filePath), $expected);
    }

    /**
     * Test that the adapter throws an exception if the file is invalid
     *
     * @test
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    public function assertAdapterThrowsExceptionIfFileIsInvalid() : void
    {
        $this->expectException(AdapterException::class);
        $this->phpFileAdapter->read('a/random/path/to/a/file.yml');
    }

    /**
     * Validation data provider
     *
     * @return array
     */
    public function validationDataProvider() : array
    {
        return [
            [__DIR__ . '/../../../fixtures/config/test1.php', true],
            ['test/path/file', false],
            ['test/path/file.yml', false],
            [__DIR__ . '/../../../fixtures/config/test2.php', true],
            ['', false]
        ];
    }

    /**
     * Read data provider
     *
     * @return array
     */
    public function readDataProvider() : array
    {
        return [
            [
                __DIR__ . '/../../../fixtures/config/test1.php',
                [
                    'test1_1' => 'test1',
                    'test1_2' => 123,
                    'test1_3' => [
                        'test1' => 'test1',
                        'test2' => 123,
                        'test3' => [
                            'test1' => 'test1',
                            'test2' => [
                                'test1' => 'test1'
                            ],
                            'test3' => [
                                'test1' => 'test1'
                            ]
                        ]
                    ]
                ]
            ],
            [
                __DIR__ . '/../../../fixtures/config/test2.php',
                [
                    'test2_1' => 'test1',
                    'test2_2' => 123,
                    'test2_3' => [
                        'test1' => 'test1',
                        'test2' => 123,
                        'test3' => [
                            'test1' => 'test1',
                            'test2' => [
                                'test1' => 'test1'
                            ],
                            'test3' => [
                                'test1' => 'test1'
                            ]
                        ]
                    ]
                ]
            ],
            [
                __DIR__ . '/../../../fixtures/config/test3.php',
                [
                    'test3_1' => 'test1',
                    'test3_2' => 123,
                    'test3_3' => [
                        'test1' => 'test1',
                        'test2' => 123,
                        'test3' => [
                            'test1' => 'test1',
                            'test2' => [
                                'test1' => 'test1'
                            ],
                            'test3' => [
                                'test1' => 'test1'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
