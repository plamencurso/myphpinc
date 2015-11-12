<?php
require_once 'inc.php'; // header
require_once 'funciones.php';
myInit(__FILE__);
$code = '
echo h3("usando " . ahref("funciones.php", "funciones.php"));

$figura = $_GET["figura"]; 
$funcion = $_GET["funcion"]; 
$n1 = $_GET["n1"]; 
$n2 = $_GET["n2"]; 
$n3 = $_GET["n3"]; 

echo "usted quiere calcular $funcion de $figura con parametros $n1 $n2 $n3 ..." . br();

$f = $funciones[$figura][$funcion]; // buscamos la funcion

if ($f !== null) // la hemos encontrado
    echo "$funcion de $figura de $n1 $n2 $n3: " . $f($n1, $n2, $n3);
else {
    echo "lo siento, no se como se hace esto todavia", br(), "se calcular estos:", br();
    foreach ($funciones as $fig => $funcs)
        echo implode(array_keys($funcs), ","), " de $fig", br();
}
'; eval($code); echo gpp($code);
// getting my URL

$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include_once "incf.php";
echo disqus_code($yo, __FILE__, "cursomm");
?>    

