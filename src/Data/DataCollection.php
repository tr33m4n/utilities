<?php

declare(strict_types=1);

namespace tr33m4n\Utilities\Data;

use Iterator;

/**
 * Class DataCollection
 *
 * @package tr33m4n\Utilities\Data
 */
class DataCollection implements DataCollectionInterface
{
    /**
     * @var array<string, mixed>
     */
    private $data = [];

    /**
     * DataCollection constructor.
     *
     * @param array<string, mixed> $dataArray Atomically add data on construct
     */
    public function __construct(array $dataArray = [])
    {
        $this->setAll($dataArray);
    }

    /**
     * @inheritDoc
     */
    public static function from(array $dataArray = []): DataCollectionInterface
    {
        return new self($dataArray);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): DataCollectionInterface
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        return $this->has($key) ? $this->data[$key] : null;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, mixed> $dataArray
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface<int|string, mixed>
     */
    public function setAll(array $dataArray): DataCollectionInterface
    {
        $this->data = $dataArray;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Iterator<int|string, mixed>
     */
    public function getIterator(): Iterator
    {
        yield from $this->data;
    }
}
