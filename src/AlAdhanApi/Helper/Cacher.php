<?php
namespace AlAdhanApi\Helper;

use AlAdhanApi\Helper\Config;

/**
 * Class Cacher
 * @package Helper\Cacher
 */
class Cacher
{
    /**
     * Memcached Object
     * @var Object
     */
    private $mc;


    /**
     * Creates the Memcached Object
     */
    public function __construct($host = null, $port = null)
    {
        $appConfig = new Config();
        $config = $appConfig->connection('memcache');

        if ($host === null) {
            $host = $config->host;
        }

        if ($port === null) {
            $port = $config->port;
        }

        $this->mc = new \Memcached();

        try {
            $this->mc->addServer($host, $port);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Generates a key for the memcached store
     * @param  String $id
     * @param  Array  $params The query parameters that make the request unique
     * @return String
     */
    public function generateKey($id, array $params)
    {
        return $id . ':' . implode('_', str_replace(' ', '', $params));
    }

    /**
     * Writes to the cache
     * @param String $k Key
     * @param String $v Value
     * @return Boolean
     */
    public function set($k, $v)
    {
        return $this->mc->set($k, $v);
    }

    /**
     * [get description]
     * @param String $k Key
     * @return Mixed
     */
    public function get($k)
    {
        return $this->mc->get($k);
    }

    /**
     * [check description]
     * @param String $k Key
     * @return Boolean
     */
    public function check($k)
    {
        $value = $this->mc->get($k);
        if ($this->mc->getResultMessage() == 'SUCCESS') {
            // Key was found irrespective of value
            return true;
        }

        return false;
    }
}
