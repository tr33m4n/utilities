<?php

use DanielDoyle\HappyUtilities\Registry;
use DanielDoyle\HappyUtilities\Exception\RegistryException;
use DanielDoyle\HappyUtilities\Config\ConfigProvider;

/**
 * Helper function for easily accessing the config provider
 *
 * @throws \DanielDoyle\HappyUtilities\Exception\MissingConfigException
 * @throws \DanielDoyle\HappyUtilities\Exception\RegistryException
 * @param string $scope                 Scope config
 * @param array  $additionalConfigPaths Additional config paths
 * @return \DanielDoyle\HappyUtilities\Config\ConfigProvider
 */
function config(string $scope = '', array $additionalConfigPaths = [])
{
    try {
        $configProvider = Registry::getConfigProvider();
    } catch (RegistryException $exception) {
        $configProvider = new ConfigProvider($additionalConfigPaths);

        Registry::setConfigProvider($configProvider);
    }

    return strlen($scope) ? $configProvider->get($scope) : $configProvider;
}
