<?php
require_once 'inc.php'; // header
require_once 'funciones.php';
myInit(__FILE__);

$code = '

echo h3("usando " . ahref("funciones.php", "funciones.php"));
echo h4("Que desea?");

echo form("formulario", "procesar_formulario.php", [
    linput("Figura", ["name" => "figura", "autofocus" => ""]),
    linput("Función", ["name" => "funcion"]),
    linput("n1", ["name" => "n1"]),                 // demasiado repetición, donde esta mi DRY ??
    linput("n2", ["name" => "n2"]),                 // lo arreglare pronto, puedo poner los name= de forma automatica
    linput("n3", ["name" => "n3"]),
    submit("Enviar", "enviar")
]);
'; eval($code); echo gpp($code);

$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";
echo disqus_code($yo, __FILE__, "cursomm");
?>    

