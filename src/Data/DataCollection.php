<?php

namespace tr33m4n\Utilities\Data;

/**
 * Class DataCollection
 *
 * @package tr33m4n\Utilities\Data
 */
class DataCollection implements DataCollectionInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * DataCollection constructor.
     *
     * @param array $dataArray Atomically add data on construct
     */
    public function __construct(array $dataArray = [])
    {
        if (!empty($dataArray)) {
            $this->setAll($dataArray);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param array $dataArray Atomically add data on construct
     * @return \tr33m4n\Utilities\Data\DataCollectionInterface
     */
    public static function from(array $dataArray = []) : DataCollectionInterface
    {
        return new self($dataArray);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key   Data key
     * @param mixed  $value Data value
     * @return $this
     */
    public function set(string $key, $value) : DataCollectionInterface
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key Data to return by key
     * @return mixed
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key Key to check
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $dataArray Data array
     * @return $this
     */
    public function setAll(array $dataArray) : DataCollectionInterface
    {
        $this->data = $dataArray;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAll() : array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getIterator()
    {
        return (function () {
            foreach ($this->data as $key => $value) {
                yield $key => $value;
            }
        })();
    }
}
