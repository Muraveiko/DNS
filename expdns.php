<?php
namespace Muraveiko\DNS;

require "vendor/autoload.php";

// JSON formatted DNS records file
$record_file = 'expired_dns.json';

$config  = new Config($record_file);
$Provider = new ExpiredProvider($record_file);


// Creating a new instance of our class
$dns = new Server($Provider, $config->getIp());

// Starting our DNS server
$dns->start();
