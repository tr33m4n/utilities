<?php

declare(strict_types=1);

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Exception\ContainerException;

/**
 * Class Container
 *
 * @package tr33m4n\Utilities\Config
 */
class Container
{
    /**
     * @var \tr33m4n\Utilities\Config\ConfigProvider
     */
    private static $configProvider;

    /**
     * Set config provider
     *
     * @param \tr33m4n\Utilities\Config\ConfigProvider $configProvider
     * @return \tr33m4n\Utilities\Config\ConfigProvider
     */
    public static function setConfigProvider(ConfigProvider $configProvider): ConfigProvider
    {
        self::$configProvider = $configProvider;

        return self::$configProvider;
    }

    /**
     * Get config provider
     *
     * @throws \tr33m4n\Utilities\Exception\ContainerException
     * @return \tr33m4n\Utilities\Config\ConfigProvider
     */
    public static function getConfigProvider(): ConfigProvider
    {
        if (!self::$configProvider instanceof ConfigProvider) {
            throw new ContainerException('Config provider must first be set!');
        }

        return self::$configProvider;
    }
}
