<?php

declare(strict_types=1);

use tr33m4n\Utilities\Config\ConfigProvider;
use tr33m4n\Utilities\Config\Container;
use tr33m4n\Utilities\Exception\ContainerException;

/**
 * Helper function for easily accessing the config provider
 *
 * @throws \tr33m4n\Utilities\Exception\AdapterException
 * @param string|null $scope                 Scope config
 * @param string[]    $additionalConfigPaths Additional config paths
 * @return \tr33m4n\Utilities\Config\ConfigProvider|mixed
 */
function config(string $scope = null, array $additionalConfigPaths = [])
{
    try {
        $configProvider = Container::getConfigProvider();
    } catch (ContainerException $containerException) {
        $configProvider = Container::setConfigProvider(new ConfigProvider());
    }

    $configProvider->addConfigPaths($additionalConfigPaths);

    return null !== $scope ? $configProvider->get($scope) : $configProvider;
}
