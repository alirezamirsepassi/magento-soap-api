<?php
namespace AlirezaMirsepassi\Magento\Support\Data;

use AlirezaMirsepassi\Magento\Contracts\Data\DataObjectInterface;

class DataObject implements DataObjectInterface, \ArrayAccess
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get string or full data from a given object
     *
     * @param string $key
     * @return mixed
     */
    public function getData(string $key = '')
    {
        if ('' === $key) {
            return $this->data;
        }
        return data_get($this->data, $key);
    }

    /**
     * Get the available functions for a DataObject
     *
     * @return mixed
     */
    public function getFunctions()
    {
        // TODO: Implement getFunctions() method.
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return data_get($this->data, $offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        return data_set($this->data, $offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        return data_get($this->data, $offset);
    }

    /**
     * @inheritdoc
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) == 'get') {
            $key = snake_case(substr($method,3));
            return $this->getData($key, isset($args[0]) ? $args[0] : null);
        }
        return null;
    }
}