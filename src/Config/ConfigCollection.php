<?php

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
     * @param array $configArray
     */
    public function __construct(array $configArray = [])
    {
        array_walk($configArray, function (&$configValue) {
            // Iterate config array and populate with new collection if is an array
            $configValue = is_array($configValue) ? self::from($configValue) : $configValue;
        });

        parent::__construct($configArray);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $configArray Atomically add data on construct
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public static function from(array $configArray = []) : DataCollectionInterface
    {
        return new self($configArray);
    }
}
