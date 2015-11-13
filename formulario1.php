<?php
require_once 'inc.php'; // header
require_once 'funciones.php';
myInit(__FILE__);

$code = '
echo h3("usando " . ahref("funciones.php", "funciones.php"));

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
        echo "$funcion de $figura de $n1 $n2 $n3: " . $f($n1, $n2, $n3); // y la usamos
    else {
        echo "lo siento, no se como se hace esto todavía", br(), "sé calcular estos:", br();
        foreach ($funciones as $fig => $funcs)
            echo implode(array_keys($funcs), ","), " de $fig", br();
    }
}

echo h4("Que desea?");

echo form([                     // mostramos siempre el formulario, procesamos en el mismo archivo
    lnvinput("figura", $figura, ["autofocus" => ""]),
    lnvinput("funcion", $funcion),
    lnvinput("n1", $n1 + 1),    // con esto podemos seguir pulsando a Entrar y nos va aumentando    
    lnvinput("n2", $n2),        // así guardarando los valores del formulario o introduciemdo diferentes
    lnvinput("n3", $n3),        // podemos usar esto para decir -- muestra me lo del cubo mayor que este -- poniendo $n1 + 1 en vez de $n1
    submit("enviar")
]);

'; eval($code); echo gpp($code);

$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";
echo disqus_code($yo, __FILE__, "cursomm");

?>    

