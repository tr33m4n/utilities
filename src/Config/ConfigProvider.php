<?php

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Exception\MissingConfigException;
use tr33m4n\Utilities\Data\DataObject;

/**
 * Class ConfigProvider
 *
 * @package tr33m4n\Utilities\Config
 */
class ConfigProvider extends DataObject
{
    /**
     * Config file extension
     */
    const CONFIG_FILE_EXTENSION = '.php';

    /**
     * @var array
     */
    private $additionalConfigPaths;

    /**
     * AbstractConfigProvider constructor.
     *
     * @param array $additionalConfigPaths
     */
    public function __construct(
        array $additionalConfigPaths = []
    ) {
        parent::__construct();

        $this->additionalConfigPaths = $additionalConfigPaths;
        $this->initConfig();
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
                            $initConfigFiles[basename($configFilePath, self::CONFIG_FILE_EXTENSION)] =
                                new ConfigCollection(include $configFilePath);

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
     * 3. Default config path
     *
     * @return array
     */
    private function getConfigPaths() : array
    {
        $configPaths = [];

        // Default config path
        $configPaths[] = __DIR__ . '/../../config';

        // Merge any additional paths
        $configPaths = array_merge($configPaths, $this->additionalConfigPaths);

        // Check if global path has been defined, and add to config array
        if (defined('ROOT_CONFIG_PATH')) {
            $configPaths[] = ROOT_CONFIG_PATH;
        }

        // Sanitise config paths and append extension
        return array_map(function (string $path) {
            return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*' . self::CONFIG_FILE_EXTENSION;
        }, $configPaths);
    }
}
