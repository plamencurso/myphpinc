<?php

require __DIR__ . '/vendor/autoload.php';

// require_once('phar:///home/ubuntu/workspace/Modulo3/PhpConsole.phar'); // autoload will be initialized automatically
require_once('phar://PhpConsole.phar'); // autoload will be initialized automatically
$connector = PhpConsole\Connector::getInstance();
$handler = PhpConsole\Handler::getInstance();
$handler->start();
// $handler->debug('called from handler debug', 'some.three.tags');
PhpConsole\Helper::register();  // para PC::db y PC::debug
PC::db((new Tidy)->getConfig(), "Tidy");

function myInit($f, $title = "") { // calling file, optional title

    if ($title == "") $title = basename($f); 
    
    echo '
<DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>' . $title . '</title>
    </head>
<body>
';

    echo "<h3><a href=" . basename($f) . ">$f</a></h3>";

    PC::db('Entering ' . $f);
    if ($_GET) PC::db($_GET, "_GET");
    if ($_POST) PC::db($_POST, "_POST");
    if ($_REQUEST) PC::db($_REQUEST, "_REQUEST");
};


?>  

