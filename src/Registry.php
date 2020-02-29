<?php

namespace tr33m4n\HappyUtilities;

use tr33m4n\HappyUtilities\Exception\RegistryException;
use tr33m4n\HappyUtilities\Config\ConfigProvider;

/**
 * Class Registry
 *
 * @package tr33m4n\HappyUtilities
 */
class Registry
{
    /**
     * Config provider key
     */
    const CONFIG_PROVIDER_KEY = 'config_provider';

    /**
     * @var array
     */
    private static $registry = [];

    /**
     * Whether key has been set
     *
     * @param mixed $key Key to check
     * @return bool
     */
    public static function has($key)
    {
        return isset(self::$registry[$key]);
    }

    /**
     * Set registry key
     *
     * @param mixed $key   Key to set
     * @param mixed $value Value to set
     * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
     */
    public static function set($key, $value)
    {
        if (self::has($key)) {
            throw new RegistryException('Registry key already exists!');
        }

        self::$registry[$key] = $value;
    }

    /**
     * Get registry value by key
     *
     * @param mixed $key Key to get
     * @return mixed|null
     */
    public static function get($key)
    {
        return self::has($key) ? self::$registry[$key] : null;
    }

    /**
     * Set config provider
     *
     * @throws RegistryException
     * @param \tr33m4n\HappyUtilities\Config\ConfigProvider $configProvider
     */
    public static function setConfigProvider(ConfigProvider $configProvider)
    {
        self::set(self::CONFIG_PROVIDER_KEY, $configProvider);
    }

    /**
     * Get config provider
     *
     * @throws \tr33m4n\HappyUtilities\Exception\RegistryException
     * @return \tr33m4n\HappyUtilities\Config\ConfigProvider
     */
    public static function getConfigProvider() : ConfigProvider
    {
        if (!self::has(self::CONFIG_PROVIDER_KEY)) {
            throw new RegistryException('Config provider has not been registered!');
        }

        return self::get(self::CONFIG_PROVIDER_KEY);
    }
}
