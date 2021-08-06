<?php

declare(strict_types=1);

use tr33m4n\Utilities\Config\ConfigProvider;

/**
 * Helper function for easily accessing the config provider
 *
 * @throws \tr33m4n\Utilities\Exception\AdapterException
 * @param string|null $scope                 Scope config
 * @param array       $additionalConfigPaths Additional config paths
 * @return \tr33m4n\Utilities\Config\ConfigProvider|mixed
 */
function config(string $scope = null, array $additionalConfigPaths = [])
{
    static $configProvider = null;
    if (!$configProvider instanceof ConfigProvider) {
        $configProvider = new ConfigProvider($additionalConfigPaths);
    }

    return null !== $scope ? $configProvider->get($scope) : $configProvider;
}
