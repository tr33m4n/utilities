<?php

namespace tr33m4n\Utilities\Data;

use IteratorAggregate;

/**
 * Interface DataObjectInterface
 *
 * @package tr33m4n\Utilities\Data
 */
interface DataObjectInterface extends IteratorAggregate
{
    /**
     * Set data
     *
     * @param string $key   Data key
     * @param mixed  $value Data value
     * @return \tr33m4n\Utilities\Data\DataObjectInterface
     */
    public function set(string $key, $value);

    /**
     * Get data
     *
     * @param string $key Data to return by key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Check if key exists
     *
     * @param string $key Key to check
     * @return bool
     */
    public function has(string $key) : bool;

    /**
     * Atomically set data array
     *
     * @param array $dataArray Data array
     * @return \tr33m4n\Utilities\Data\DataObjectInterface
     */
    public function setAll(array $dataArray);

    /**
     * Get all data
     *
     * @return array
     */
    public function getAll() : array;
}
