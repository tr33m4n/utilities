<?php

namespace HappyUtilities\Helpers;

use HappyUtilities\Exceptions\MissingConfigException;
use HappyUtilities\Data\DataObject;

/**
 * Class Config
 *
 * @package HappyUtilities\Helpers
 *
 * @author  Daniel Doyle <dd@amp.co>
 */
class Config extends DataObject
{
    /**
     * Config path override
     */
    const HAPPYUTILITIES_CONFIG_PATH = '';

    /**
     * Config constructor.
     *
     * @param string $configType Config type
     */
    public function __construct(string $configType = '')
    {
        parent::__construct();

        $this->setConfig($configType);
    }

    /**
     * Set config
     *
     * @author Daniel Doyle <dd@amp.co>
     * @throws \HappyUtilities\Exceptions\MissingConfigException
     * @param string $configType Config type
     * @return void
     */
    protected function setConfig($configType)
    {
        $configPath = $this->getConfigPath() . '*.php';

        // If a config type is provided, update path to only select specified type
        if (strlen($configType)) {
            $configPath = $this->getConfigPath() . $configType . '.php';
        }

        // Glob config path, returning an array of matching paths
        $configFiles = glob($configPath);

        if (empty($configFiles)) {
            throw new MissingConfigException('There are no config files present in the config directory!');
        }

        foreach ($configFiles as $configFilePath) {
            // If we only have one path to set, presume specific config requested so atomically set all and break
            if (strlen($configType)) {
                $this->setAll(include $configFilePath);
                break;
            }

            // Set all config paths by basename
            $this->set(basename($configFilePath, '.php'), include $configFilePath);
        }
    }

    /**
     * Get config path
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return string
     */
    protected function getConfigPath() : string
    {
        // Default path
        $configPath = dirname(__FILE__) . '/../../config';

        // Check if class has path overrides and set those instead of default
        if (strlen(static::HAPPYUTILITIES_CONFIG_PATH)) {
            $configPath = static::HAPPYUTILITIES_CONFIG_PATH;
        }

        // Check if global path has been defined, and set that instead of default or class
        if (defined('HAPPYUTILITIES_CONFIG_PATH')) {
            $configPath = HAPPYUTILITIES_CONFIG_PATH;
        }

        // Ensure the resulting path has a trailing slash
        return rtrim($configPath, '/') . '/';
    }
}
