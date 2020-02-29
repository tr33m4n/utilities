<?php

namespace tr33m4n\Utilities\Config;

use tr33m4n\Utilities\Data\DataObject;

/**
 * Class ConfigCollection
 *
 * @package tr33m4n\Utilities\Config
 */
class ConfigCollection extends DataObject
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
            $configValue = is_array($configValue) ? new self($configValue) : $configValue;
        });

        parent::__construct($configArray);
    }
}
