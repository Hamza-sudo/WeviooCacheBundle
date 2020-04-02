<?php

namespace Wevioo\WeviooCacheBundle\Component;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Yaml\Yaml;

class WeviooCache
{
    private $cache;
    private $type;
    private $host;
    private $port;
    private $username;
    private $password;
    private $db_name;


    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(string $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDbName(): ?string
    {
        return $this->db_name;
    }

    public function setDbName(?string $db_name): self
    {
        $this->db_name = $db_name;

        return $this;
    }


    /**
     *
     * @param $path
     * @param $source
     * @return array|null
     */
    public function configurationsCache($path, $source){
        if ($source === 'REDIS'){
            $value = Yaml::parseFile($path);
            $host = $value['redis']['host'];
            $port = $value['redis']['port'];
            return array('host' =>$host,'port'=>$port);
        }

        else if ($source === 'MYSQL') {
            $value = Yaml::parseFile($path);
            $user = $value['mysql']['user'];
            $pass = $value['mysql']['password'];
            $host = $value['mysql']['host'];
            $port = $value['mysql']['port'];
            $db_name = $value['mysql']['db_name'];
            return array('host' =>$host,'port'=>$port,'user'=>$user,'pwd'=>$pass,'db_name'=>$db_name);
        }

        return null;
    }

    /**
     * Allows to return the data from the cache (through the key) otherwise return null
     *
     * @param String $source
     * @param String $name
     * @return mixed|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getCache($source, $name)
    {
        if (file_exists('../config/wevioo_cache.yaml')) {
            if($source === 'API') {
                return null;
            } else if($source === 'REDIS') {
                $config = $this->configurationsCache('../config/wevioo_cache.yaml',$source);
                $client = RedisAdapter::createConnection("redis://".$config['host'].":".$config['port']);
                $cache = new RedisAdapter($client);
                $cached = $cache->getItem($name);
                if ($cached->isHit()) {
                    return $cached->get();
                }
                return null;
            } else if($source === 'MYSQL') {
                $config = $this->configurationsCache('../config/wevioo_cache.yaml',$source);
                $cache = new PdoAdapter("mysql://".$config['user'].":".$config['pwd']."@".$config['host'].":".$config['port']."/".$config['db_name']);
                $cached = $cache->getItem($name);
                if ($cached->isHit()) {
                    return $cached->get();
                }
                return null;
            }
        }
        else {
            if($source === 'API') {
                return null;
            } else if($source === 'REDIS') {
                $config = array('host' =>$this->getHost(),'port'=>$this->getPort());
                $client = RedisAdapter::createConnection("redis://".$config['host'].":".$config['port']);
                $cache = new RedisAdapter($client);
                $cached = $cache->getItem($name);
                if ($cached->isHit()) {
                    return $cached->get();
                }
                return null;
            } else if($source === 'MYSQL') {
                $config = array('host' =>$this->getHost(),'port'=>$this->getPort(),'user'=>$this->getUsername(),'pwd'=>$this->getPassword(),'db_name'=>$this->getDbName());
                $cache = new PdoAdapter("mysql://".$config['user'].":".$config['pwd']."@".$config['host'].":".$config['port']."/".$config['db_name']);
                $cached = $cache->getItem($name);
                if ($cached->isHit()) {
                    return $cached->get();
                }
                return null;
            }
        }
        return null;

    }

    /**
     * description :this method stores data in the cache
     * @param String $source
     * @param String $name
     * @return mixed|null
     * @throws \Psr\Cache\InvalidArgumentException
     */

    public function saveCache($data, $source, $name, $expired) {
        if (file_exists('../config/wevioo_cache.yaml')) {
            if($source === 'API') {
                return null;
            } else if($source === 'REDIS') {
                $config = $this->configurationsCache('../config/wevioo_cache.yaml',$source);
                $client = RedisAdapter::createConnection("redis://".$config['host'].":".$config['port']);
                $cache = new RedisAdapter($client);
                $cached = $cache->getItem($name);
                $cached->set($data);
                $cached->expiresAfter($expired);
                $cache->save($cached);
            } else if($source === 'MYSQL') {
                $config = $this->configurationsCache('../config/wevioo_cache.yaml',$source);
                $cache = new PdoAdapter("mysql://".$config['user'].":".$config['pwd']."@".$config['host'].":".$config['port']."/".$config['db_name']);
                $cached = $cache->getItem($name);
                $cached->set($data);
                $cached->expiresAfter($expired);
                $cache->save($cached);
            }
        }
        else {
            if($source === 'API') {
                return null;
            } else if($source === 'REDIS') {
                $config = array('host' =>$this->getHost(),'port'=>$this->getPort());
                $client = RedisAdapter::createConnection("redis://".$config['host'].":".$config['port']);
                $cache = new RedisAdapter($client);
                $cached = $cache->getItem($name);
                $cached->set($data);
                $cached->expiresAfter($expired);
                $cache->save($cached);
            } else if($source === 'MYSQL') {
                $config = array('host' =>$this->getHost(),'port'=>$this->getPort(),'user'=>$this->getUsername(),'pwd'=>$this->getPassword(),'db_name'=>$this->getDbName());
                $cache = new PdoAdapter("mysql://".$config['user'].":".$config['pwd']."@".$config['host'].":".$config['port']."/".$config['db_name']);
                $cached = $cache->getItem($name);
                $cached->set($data);
                $cached->expiresAfter($expired);
                $cache->save($cached);
            }
        }
        return null;
    }
}
