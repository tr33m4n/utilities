<?php

namespace tr33m4n\Utilities\Config\Adapter;

use tr33m4n\Utilities\Exception\AdapterException;

/**
 * Class PhpFileAdapter
 *
 * @package tr33m4n\Utilities\Config\Adapter
 */
final class PhpFileAdapter implements FileAdapterInterface
{
    /**
     * PHP file extension
     */
    const FILE_EXTENSION = 'php';

    /**
     * {@inheritdoc}
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @param string $filePath
     * @return array
     */
    public function read(string $filePath) : array
    {
        if (!$this->validate($filePath)) {
            throw new AdapterException(sprintf('File type is invalid %s', $filePath));
        }

        $fileContents = include $filePath;
        if (!$fileContents) {
            throw new AdapterException(sprintf('Unable to include file %s', $filePath));
        }

        return $fileContents;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $filePath
     * @return bool
     */
    public function validate(string $filePath) : bool
    {
        return file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === self::FILE_EXTENSION;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function getFileExtension() : string
    {
        return self::FILE_EXTENSION;
    }
}
