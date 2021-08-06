<?php

declare(strict_types=1);

use tr33m4n\Utilities\Config\ConfigProvider;

/**
 * Helper function for easily accessing the config provider
 *
 * @throws \tr33m4n\Utilities\Exception\AdapterException
 * @param array  $additionalConfigPaths Additional config paths
 * @param string $scope                 Scope config
 * @return \tr33m4n\Utilities\Config\ConfigProvider|mixed
 */
function config(string $scope = '', array $additionalConfigPaths = [])
{
    static $configProvider = null;
    if (!$configProvider instanceof ConfigProvider) {
        $configProvider = new ConfigProvider($additionalConfigPaths);
    }

    return strlen($scope) ? $configProvider->get($scope) : $configProvider;
}
