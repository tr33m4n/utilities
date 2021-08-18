<?php

declare(strict_types=1);

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Data\DataCollection;
use tr33m4n\Utilities\Data\DataCollectionInterface;

/**
 * Class ConfigCollection
 *
 * @package tr33m4n\Utilities\Config
 */
class ConfigCollection extends DataCollection
{
    /**
     * ConfigCollection constructor.
     *
     * @param array<string, mixed> $configArray
     */
    public function __construct(array $configArray = [])
    {
        parent::__construct(array_map(static function ($configValue) {
            // Iterate config array and populate with new collection if is an array
            return is_array($configValue) ? self::from($configValue) : $configValue;
        }, $configArray));
    }

    /**
     * @inheritDoc
     */
    public static function from(array $dataArray = []): DataCollectionInterface
    {
        return new self($dataArray);
    }
}
