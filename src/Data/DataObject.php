<?php

namespace DanielDoyle\HappyUtilities\Data;

/**
 * Class DataObject
 *
 * @package DanielDoyle\HappyUtilities\Data
 */
class DataObject implements DataObjectInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * DataObject constructor.
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
     * @param string $key   Data key
     * @param mixed  $value Data value
     * @return $this
     */
    public function set(string $key, $value)
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
            return false;
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
    public function setAll(array $dataArray)
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
            while (list($key, $val) = each($this->data)) {
                yield $key => $val;
            }
        })();
    }
}
