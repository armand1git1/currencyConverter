<?php

header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self';"); // CSP implementation 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

use Dotenv\Dotenv;
// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();



?>