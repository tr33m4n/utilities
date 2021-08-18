<?php

declare(strict_types=1);

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Config\Adapter\FileAdapterInterface;
use tr33m4n\Utilities\Config\Adapter\PhpFileAdapter;
use tr33m4n\Utilities\Data\DataCollection;
use tr33m4n\Utilities\Data\DataCollectionInterface;

/**
 * Class ConfigProvider
 *
 * @package tr33m4n\Utilities\Config
 */
class ConfigProvider extends DataCollection
{
    /**
     * @var string[]
     */
    private $configPaths;

    /**
     * @var \tr33m4n\Utilities\Config\Adapter\FileAdapterInterface
     */
    private $fileAdapter;

    /**
     * ConfigProvider constructor.
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param \tr33m4n\Utilities\Config\Adapter\FileAdapterInterface|null $fileAdapter
     * @param string[]                                                    $configPaths
     */
    public function __construct(
        array $configPaths = [],
        FileAdapterInterface $fileAdapter = null
    ) {
        parent::__construct();

        $this->configPaths = $configPaths;
        $this->fileAdapter = $fileAdapter ?? new PhpFileAdapter();

        $this->initConfig();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string[] $dataArray
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public static function from(array $dataArray = []): DataCollectionInterface
    {
        return new self($dataArray);
    }

    /**
     * Add config paths
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string[] $configPaths
     * @return \tr33m4n\Utilities\Config\ConfigProvider
     */
    public function addConfigPaths(array $configPaths): ConfigProvider
    {
        $this->add($this->processConfigPaths($configPaths));

        return $this;
    }

    /**
     * Process config path
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string $configPath
     * @return array<string, \tr33m4n\Utilities\Config\ConfigCollection>
     */
    private function processConfigPath(string $configPath): array
    {
        return array_reduce(
            glob(
                rtrim($configPath, DIRECTORY_SEPARATOR) // Sanitise config paths and append extension
                    . DIRECTORY_SEPARATOR
                    . '*'
                    . '.'
                    . $this->fileAdapter::getFileExtension()
            ) ?: [],
            function (array $configFiles, string $configFilePath): array {
                $configFiles[basename($configFilePath, '.' . $this->fileAdapter::getFileExtension())] =
                    ConfigCollection::from($this->fileAdapter->read($configFilePath));

                return $configFiles;
            },
            []
        );
    }

    /**
     * Process config paths
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string[] $configPaths
     * @return array<string, \tr33m4n\Utilities\Config\ConfigCollection>
     */
    private function processConfigPaths(array $configPaths): array
    {
        return array_reduce(
            $configPaths,
            function (array $configPaths, string $configPath): array {
                return $configPaths = array_merge($configPaths, $this->processConfigPath($configPath));
            },
            []
        );
    }

    /**
     * Init config
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    private function initConfig(): void
    {
        $this->setAll($this->processConfigPaths($this->initConfigPaths()));
    }

    /**
     * Init config paths. Config preference is in the order of:
     *
     * 1. Global path
     * 2. Additional paths passed to the constructor
     *
     * @return string[]
     */
    private function initConfigPaths(): array
    {
        // Check if global path has been defined, and add to path array
        if (defined('ROOT_CONFIG_PATH')) {
            $this->configPaths[] = ROOT_CONFIG_PATH;
        }

        return $this->configPaths;
    }
}
