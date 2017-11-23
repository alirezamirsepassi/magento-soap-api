<?php
namespace AlirezaMirsepassi\Magento\Contracts\Data;

interface DataObjectInterface
{
    /**
     * Get string or full data from a given object
     *
     * @param string $key
     * @return mixed
     */
    public function getData(string $key = '');

    /**
     * Get the available functions for a DataObject
     *
     * @return mixed
     */
    public function getFunctions();
}