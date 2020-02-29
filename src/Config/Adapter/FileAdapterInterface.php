<?php

namespace tr33m4n\Utilities\Config\Adapter;

/**
 * Interface FileAdapterInterface
 *
 * @package tr33m4n\Utilities\Config\Adapter
 */
interface FileAdapterInterface
{
    /**
     * Read file
     *
     * @param string $filePath
     * @return array
     */
    public function read(string $filePath) : array;

    /**
     * Validate file
     *
     * @param string $filePath
     * @return bool
     */
    public function validate(string $filePath) : bool;

    /**
     * Get adapter file extension
     *
     * @return string
     */
    public static function getFileExtension() : string;
}
