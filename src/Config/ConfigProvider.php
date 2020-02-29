<?php

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Config\Adapter\FileAdapterInterface;
use tr33m4n\Utilities\Config\Adapter\PhpFileAdapter;
use tr33m4n\Utilities\Data\DataCollection;
use tr33m4n\Utilities\Data\DataCollectionInterface;
use tr33m4n\Utilities\Exception\MissingConfigException;

/**
 * Class ConfigProvider
 *
 * @package tr33m4n\Utilities\Config
 */
class ConfigProvider extends DataCollection
{
    /**
     * @var array
     */
    private $configPaths;

    /**
     * @var \tr33m4n\Utilities\Config\Adapter\FileAdapterInterface
     */
    private $fileAdapter;

    /**
     * ConfigProvider constructor.
     *
     * @param array                                                       $configPaths
     * @param \tr33m4n\Utilities\Config\Adapter\FileAdapterInterface|null $fileAdapter
     */
    public function __construct(
        array $configPaths = [],
        FileAdapterInterface $fileAdapter = null
    ) {
        parent::__construct();

        $this->configPaths = $configPaths;
        $this->fileAdapter = $fileAdapter ?: new PhpFileAdapter();
        $this->initConfig();
    }

    /**
     * {@inheritdoc}
     *
     * @param array $configPaths Atomically add data on construct
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public static function from(array $configPaths = []) : DataCollectionInterface
    {
        return new self($configPaths);
    }

    /**
     * Set config
     *
     * @return void
     */
    private function initConfig() : void
    {
        $this->setAll(
            array_reduce(
                $this->getConfigPaths(),
                function (array $initConfigFiles, string $rootConfigPath) {
                    if (($configFiles = glob($rootConfigPath)) === false) {
                        throw new MissingConfigException('An error occurred whilst iterating the config paths!');
                    }

                    return $initConfigFiles = array_reduce(
                        $configFiles,
                        function (array $initConfigFiles, string $configFilePath) {
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
     * @return array
     */
    private function getConfigPaths() : array
    {
        // Check if global path has been defined, and add to path array
        if (defined('ROOT_CONFIG_PATH')) {
            $this->configPaths[] = ROOT_CONFIG_PATH;
        }

        // Sanitise config paths and append extension
        return array_map(function (string $path) {
            return rtrim($path, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . '*'
                . '.'
                . $this->fileAdapter::getFileExtension();
        }, $this->configPaths);
    }
}
