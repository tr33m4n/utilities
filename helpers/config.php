<?php

use tr33m4n\Utilities\Registry;
use tr33m4n\Utilities\Exception\RegistryException;
use tr33m4n\Utilities\Config\ConfigProvider;

/**
 * Helper function for easily accessing the config provider
 *
 * @throws \tr33m4n\Utilities\Exception\RegistryException
 * @param array  $additionalConfigPaths Additional config paths
 * @param string $scope                 Scope config
 * @return \tr33m4n\Utilities\Config\ConfigProvider
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
