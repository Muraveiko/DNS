<?php

namespace Muraveiko\DNS;

use \Exception;

class Config {
    use LoadJson;

    private $config;

    public function __construct($config_file)
    {
        $this->config = $this->loadJson($config_file);
    }

    public function getIp()
    {
       return isset($this->config['server']['ip'])?$this->config['server']['ip']:'0.0.0.0';
    }

}
