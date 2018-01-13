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
     * Config constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setConfig();
    }

    /**
     * Set config
     *
     * @author Daniel Doyle <dd@amp.co>
     * @throws \HappyUtilities\Exceptions\MissingConfigException
     * @return void
     */
    public function setConfig()
    {
        $configFiles = glob($this->getConfigPath() . '*.php');

        if (empty($configFiles)) {
            throw new MissingConfigException('There are no config files present in the config directory!');
        }

        // For each config file, merge into data array
        foreach ($configFiles as $configFilePath) {
            $this->setAll($this->getAll() + include $configFilePath);
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
        if (!defined('HAPPYUTILITIES_CONFIG_PATH')) {
            return dirname(__FILE__) . '/../../config/';
        }

        return rtrim(HAPPYUTILITIES_CONFIG_PATH, '/') . '/';
    }
}
