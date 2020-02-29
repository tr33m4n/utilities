<?php

namespace tr33m4n\HappyUtilities\Config;

use tr33m4n\HappyUtilities\Exception\MissingConfigException;
use tr33m4n\HappyUtilities\Data\DataObject;

/**
 * Class ConfigProvider
 *
 * @package tr33m4n\HappyUtilities\Config
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
     * @throws MissingConfigException
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
     * @throws \tr33m4n\HappyUtilities\Exception\MissingConfigException
     * @return void
     */
    private function initConfig()
    {
        $distinctConfigFiles = [];
        foreach ($this->getConfigPaths() as $configPath) {
            $configFiles = glob($configPath);

            if ($configFiles === false) {
                throw new MissingConfigException('An error occurred whilst iterating the config paths!');
            }

            foreach ($configFiles as $configFile) {
                $configFileSlug = basename($configFile, self::CONFIG_FILE_EXTENSION);

                /**
                 * If the config file has already been defined in the $distinctConfigFiles array, skip. Due to the order
                 * in which the config files are loaded, this should ensure the global > local > default priority
                 */
                if (array_key_exists($configFileSlug, $distinctConfigFiles)) {
                    continue;
                }

                // Include config file
                $distinctConfigFiles[$configFileSlug] = new ConfigItem(include $configFile);
            }
        }

        if (empty($distinctConfigFiles)) {
            throw new MissingConfigException('There are no config files present in the config directories!');
        }

        $this->setAll($distinctConfigFiles);
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
    protected function getConfigPaths() : array
    {
        $configPaths = [];

        // Check if global path has been defined, and add to config array
        if (defined('HAPPYUTILITIES_CONFIG_PATH')) {
            $configPaths[] = HAPPYUTILITIES_CONFIG_PATH;
        }

        // Merge any additional paths
        $configPaths = array_merge($configPaths, $this->additionalConfigPaths);
        // Default config path
        $configPaths[] = __DIR__ . '/../../config';

        // Sanitise config paths and append extension
        return array_map(function (string $path) {
            return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*' . self::CONFIG_FILE_EXTENSION;
        }, $configPaths);
    }
}
