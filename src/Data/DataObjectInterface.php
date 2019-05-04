<?php

namespace DanielDoyle\HappyUtilities\Data;

/**
 * Interface DataObjectInterface
 *
 * @package DanielDoyle\HappyUtilities\Data
 */
interface DataObjectInterface extends \IteratorAggregate
{
    /**
     * Set data
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key   Data key
     * @param mixed  $value Data value
     * @return $this
     */
    public function set(string $key, $value);

    /**
     * Get data
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key Data to return by key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Check if key exists
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param string $key Key to check
     * @return bool
     */
    public function has(string $key) : bool;

    /**
     * Atomically set data array
     *
     * @author Daniel Doyle <dd@amp.co>
     * @param array $dataArray Data array
     * @return $this
     */
    public function setAll(array $dataArray);

    /**
     * Get all data
     *
     * @author Daniel Doyle <dd@amp.co>
     * @return array
     */
    public function getAll() : array;
}
