<?php

if (! file_exists(__DIR__ . '/vendor/autoload.php')) {
    die("É necessário instalar dependências: composer install");
}

require __DIR__ . '/vendor/autoload.php';

use JonasOF\RSSFatecSP\Configuracao;
use JonasOF\RSSFatecSP\RSSFatecSP;

$di = new DI\ContainerBuilder();
$container = $di->build();
$config = $container->get(Configuracao::class)->config();

if ($config['debug'] === true) {
    error_reporting(E_ALL);
    ini_set("display_errors", true);
}

$container->get(RSSFatecSP::class)->gerarRSS();