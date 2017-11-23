<?php
namespace AlirezaMirsepassi\Magento\Facades;

use Illuminate\Support\Facades\Facade;

class Magento extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'magento';
    }
}