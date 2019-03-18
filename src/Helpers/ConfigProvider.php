<?php

namespace DanielDoyle\HappyUtilities\Helpers;

use DanielDoyle\HappyUtilities\Exceptions\MissingConfigException;
use DanielDoyle\HappyUtilities\Data\DataObject;

/**
 * Class ConfigProvider
 *
 * @package DanielDoyle\HappyUtilities\Helpers
 */
class ConfigProvider extends DataObject
{
    /**
     * Config file extension
     */
    const CONFIG_FILE_EXTENSION = '.php';

    /**
     * @var string
     */
    private $scope;

    /**
     * @var array
     */
    private $additionalConfigPaths;

    /**
     * AbstractConfigProvider constructor.
     *
     * @param string|null $scope
     * @param array       $additionalConfigPaths
     */
    public function __construct(
        string $scope = null,
        array $additionalConfigPaths = []
    ) {
        parent::__construct();

        $this->additionalConfigPaths = $additionalConfigPaths;
        $this->scope = $scope;

        $this->initConfig();
    }

    /**
     * Set config
     *
     * @author Daniel Doyle <dd@amp.co>
     * @throws \DanielDoyle\HappyUtilities\Exceptions\MissingConfigException
     * @return void
     */
    protected function initConfig()
    {
        $getConfigFileSlug = function (string $filePath) {
            return basename($filePath, self::CONFIG_FILE_EXTENSION);
        };

        $distinctConfigFiles = [];
        foreach ($this->getConfigPaths() as $configPath) {
            $configFiles = glob($configPath);

            if ($this->scope) {
                // Reduce file paths to those that match the scope
                array_filter($configFiles, function ($path) use ($getConfigFileSlug) {
                    return $getConfigFileSlug($path) === $this->scope;
                });
            }

            foreach ($configFiles as $configFile) {
                $configFileSlug = $getConfigFileSlug($configFile);

                /**
                 * If the config file has already been defined in the $distinctConfigFiles array, skip. Due to the order
                 * in which the config files are loaded, this should ensure the global > local > default priority
                 */
                if (array_key_exists($configFileSlug, $distinctConfigFiles)) {
                    continue;
                }

                // Include config file
                $distinctConfigFiles[$configFileSlug] = include $configFile;
            }
        }

        if (empty($distinctConfigFiles)) {
            throw new MissingConfigException('There are no config files present in the config directories!');
        }

        $this->setAll($this->scope ? $distinctConfigFiles[$this->scope] : $distinctConfigFiles);
    }

    /**
     * Get config paths. Config preference is in the order of:
     *
     * 1. Global path
     * 2. Additional paths passed to the constructor
     * 3. Default config path
     *
     * @author Daniel Doyle <dd@amp.co>
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
