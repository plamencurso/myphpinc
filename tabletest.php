<?php
$editing = true;  // do not include disqus
include_once "inc.php"; // header
myInit(__FILE__);
#$code = '
require_once "funciones.php";

// ejemplo pedido - [[base imponible, articulo, cantidad]]
$titulos = ["precio", "cosa", "cantidad"];
$pedido = [
    [1.50, "cocacola", 5],
    [1.30, "café", 4],
    [1.30, "cerveza", 12],
    [3.50, "otra cosa", 3.9],  // la 4ª me superó :(
];

echo "tabla simple" . table($titulos, $pedido);
echo br() . "parece que HTML5 quita todos los atributos de TABLE (they say use CSS), queda uno - sortable" .
    table($titulos, $pedido, ["sortable" => "sortable"]);

echo br() . "probamos border" .
    table($titulos, $pedido, ["border" => ""]);

echo br() . "ponemos classes y no lo torturamos mas (ver codigo fuente)" .
    table($titulos, $pedido, ["class" => "mytable"], ["class" => "myth"], ["class" => "mytr"], ["class" => "mytd"]);

#'; eval($code); echo gpp($code);
// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
?>    

