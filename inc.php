<DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
    </head>
<body>

<?php

require __DIR__ . '/vendor/autoload.php';
// require_once('phar:///home/ubuntu/workspace/Modulo3/PhpConsole.phar'); // autoload will be initialized automatically
require_once('phar://PhpConsole.phar'); // autoload will be initialized automatically

use League\CommonMark\CommonMarkConverter;
$converter = new CommonMarkConverter();
//echo $converter->convertToHtml('### ' . __FILE__);

$connector = PhpConsole\Connector::getInstance();
$handler = PhpConsole\Handler::getInstance();
$handler->start();
// $handler->debug('called from handler debug', 'some.three.tags');
PhpConsole\Helper::register();  // para PC::db y PC::debug
//PC::db('Entering ' . __FILE__);

//PC::db($_GET);

function md($t) {
    global $converter;
    return $converter->convertToHtml($t);    
};

function myInit($f){
    echo md('### ' . $f);

    PC::db('Entering ' . $f);
    if ($_GET) PC::db($_GET, "_GET");
};


?>  

