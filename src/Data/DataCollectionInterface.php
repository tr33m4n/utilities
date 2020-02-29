<?php

namespace tr33m4n\Utilities\Data;

use IteratorAggregate;

/**
 * Interface DataCollectionInterface
 *
 * @package tr33m4n\Utilities\Data
 */
interface DataCollectionInterface extends IteratorAggregate
{
    /**
     * Create statically from data array
     *
     * @param array $dataArray Atomically add data on construct
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public static function from(array $dataArray = []) : DataCollectionInterface;

    /**
     * Set data
     *
     * @param string $key   Data key
     * @param mixed  $value Data value
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public function set(string $key, $value) : DataCollectionInterface;

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
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public function setAll(array $dataArray) : DataCollectionInterface;

    /**
     * Get all data
     *
     * @return array
     */
    public function getAll() : array;
}
