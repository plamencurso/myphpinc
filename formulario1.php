<?php
require_once 'inc.php'; // header
require_once 'funciones.php';
myInit(__FILE__);

// $code = '
$figura = $funcion = $n1 = $n2 = $n3 = ""; // esto vale solo la primera invocacion, luego lo coje del formulario

if (isset($_GET["enviar"])) { // tenemos datos, procesamos y mostramos
    $figura = $_GET["figura"]; 
    $funcion = $_GET["funcion"]; 
    $n1 = $_GET["n1"]; 
    $n2 = $_GET["n2"]; 
    $n3 = $_GET["n3"]; 
    
    echo "quiere calcular $funcion de $figura con parametro(s) $n1 $n2 $n3 ..." . br();
    
    $f = $funciones[$figura][$funcion]; // buscamos la funcion (usar array_key_exists() mejor)
    
    if ($f !== null) // la hemos encontrado
        echo "$funcion de $figura de $n1 $n2 $n3: " . $f($n1, $n2, $n3);
    else {
        echo "lo siento, no se como se hace esto todavia", br(), "se calcular estos:", br();
        foreach ($funciones as $fig => $funcs)
            echo implode(array_keys($funcs), ","), " de $fig", br();
    }
}

echo h3("usando " . ahref("funciones.php", "funciones.php"));
echo h4("Que desea?");

echo form("formulario", basename($_SERVER["SCRIPT_NAME"]), [  // mostramos siempre el formulario, procesamos en el mismo archivo
    lninput("figura", ["autofocus" => ""]),         // primero probamos lniput()
    lninput("funcion"),
    lninput("n1"),
    lninput("n2"),
    lninput("n3"),
    submit("enviar")
]);

//'; eval($code); echo gpp($code);
/*
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";
echo disqus_code($yo, __FILE__, "cursomm");
*/
?>    

