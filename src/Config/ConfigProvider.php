<?php

declare(strict_types=1);

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Config\Adapter\FileAdapterInterface;
use tr33m4n\Utilities\Config\Adapter\PhpFileAdapter;
use tr33m4n\Utilities\Data\DataCollection;

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
     * Set config
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return void
     */
    private function initConfig(): void
    {
        $this->setAll(
            array_reduce(
                $this->getConfigPaths(),
                function (array $initConfigFiles, string $rootConfigPath): array {
                    return $initConfigFiles = array_reduce(
                        glob($rootConfigPath) ?: [],
                        function (array $initConfigFiles, string $configFilePath): array {
                            $initConfigFiles[basename($configFilePath, '.' . $this->fileAdapter::getFileExtension())] =
                                ConfigCollection::from($this->fileAdapter->read($configFilePath));

                            return $initConfigFiles;
                        },
                        $initConfigFiles
                    );
                },
                []
            )
        );
    }

    /**
     * Get config paths. Config preference is in the order of:
     *
     * 1. Global path
     * 2. Additional paths passed to the constructor
     *
     * @return string[]
     */
    private function getConfigPaths(): array
    {
        // Check if global path has been defined, and add to path array
        if (defined('ROOT_CONFIG_PATH')) {
            $this->configPaths[] = ROOT_CONFIG_PATH;
        }

        // Sanitise config paths and append extension
        return array_map(function (string $path): string {
            return rtrim($path, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . '*'
                . '.'
                . $this->fileAdapter::getFileExtension();
        }, $this->configPaths);
    }
}
