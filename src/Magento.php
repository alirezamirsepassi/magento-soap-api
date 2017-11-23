<?php
namespace AlirezaMirsepassi\Magento;

use Illuminate\Contracts\Config\Repository;
use AlirezaMirsepassi\Magento\Api\Soap\Client;

class Magento
{
    /**
     * Default connection name
     */
    const DEFAULT_CONNECTION_NAME = 'default';
    /**
     * @var Repository $config
     */
    protected $config;

    /**
     * @var array $connections
     */
    protected $connections = [];

    /**
     * @var null
     */
    protected $connection = null;

    /**
     * Constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;

        $this->registerConnections();
    }

    /**
     * Get array of available SOAP connections
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Get the current connection or fallback to the default connection
     *
     * @return mixed|null
     */
    public function getConnection()
    {
        if (is_null($this->connection)) {
            $this->connection = $this->connections[self::DEFAULT_CONNECTION_NAME];
        }
        return $this->connection;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setConnection($key)
    {
        $this->connection = array_get($this->connections, $key);
        return $this;
    }

    /**
     * Create a new connection on the fly
     *
     * @param $name
     * @param array $options
     * @return \InvalidArgumentException
     */
    public function create($name, array $options = [])
    {
        if (!isset($this->connections[$name])) {
            $this->connections = array_merge($this->connections, [$name => $options]);
            return $this->setConnection($name);
        }
        throw new \InvalidArgumentException("Connection [$name] already exists");
    }

    /**
     * Make SOAP request for Version 1
     *
     * @param $method string
     * @param array $arguments
     * @return mixed
     */
    public function get($method, $arguments = [])
    {
        return $this->client()->call($method, $arguments);
    }

    /**
     * Registers default configuration connections
     */
    protected function registerConnections()
    {
        $this->connections = $this->config->get('magento.connections') ?: [];
    }

    /**
     * Return new instance of Soap Client
     *
     * @return Client
     */
    protected function client()
    {
        return new Client($this->getConnection());
    }

    /**
     * Slight modification to allow for SOAP methods to be forwarded to client
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func($name, $arguments);
        }
        return $this->client()->__soapCall($name, $arguments);
    }
}