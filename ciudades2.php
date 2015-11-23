<?php
$editing = true;  // do not include disqus
require "inc.php"; // header
myInit(__FILE__);
#$code = '
$yo = basename($_SERVER["SCRIPT_NAME"]);  // para enlaces relativos a este mismo script

// ESTO NO FUNCIONA ... MUCHO

// DATOS

// son 2 relaciones 1 a N
$continentes = ["Europa", "Asia"];

$paises["Europa"] = ["España", "Portugal"];
$ciudades["España"] = ["Madrid", "Barcelona"];
$ciudades["Portugal"] = ["Lisboa", "Porto"];

$paises["Asia"] = ["China", "India"];
$ciudades["China"] = ["Beijing", "Shanghai"];
$ciudades["India"] = ["Mumbai", "Calcutta"];

// FIN DE DATOS

// PROGRAMA PRINCIPAL

// usa unordered lists, se puede presentar algo mejor con un poco de CSS

// los parametros del script: co(ntinente) pa(is) ci(udad)
param("co", $co);     // param() cambia(defina) el valor de la variable(global) 2º argumento
param("pa", $pa);     // mira lo que hace extract(), todavía no lo uso aquí
param("ci", $ci);     // seria buena idea si son muchos parametros

// empezamos el query string para pasarlo a hacer_lista()
$qs = "$yo";

// mostrar siempre los continentes
echo hacer_lista("Continentes", "la Tierra", "?co", $continentes);

if ($co) {       // mostrar paises si se ha selecionado un continente
    $qs .= "?co=$co";
    echo hacer_lista("Paises", $co, "&pa", $paises[$co]);

    // mostrar ciudades si se ha selecionado un país
    if ($pa) { 
        $qs .= "&pa=$pa";
        echo hacer_lista("Ciudades", $pa, "&ci", $ciudades[$pa]);
        
        // mostrar la ciudad seleccionada
        if ($ci) echo "ha seleccionado $ci";
    }
}

// FIN DE PROGRAMA PRINCIPAL

// FUNCIONES

// mucho cuidao aquí, modificamos un variable - $v
function param($p, &$v) {                                   // prueba si tenemos el dato en _GET y lo asigna a $v, devuelve el valor actual o "" 
    return $v = (isset($_GET) && array_key_exists($p, $_GET)) ? $_GET[$p] : "";    // aqui modificamos el variable pasada, y devolvemos el valor también
}

function hacer_lista($q, $dq, $p, $l) {    // hacer la lista de que deque, nombre de parametro, lista de datos
    $res = "$q de $dq:<ul>";         // inicio de unordered list, aqui acumulamos el resultado
    
    foreach ($l as $li) $res .= "<li>" . hacer_vinculo($p, $li) . "</li>";

    return "$res</ul>";
}

function hacer_vinculo($p, $v) {           // parametro, value 
    global $qs;
    
    return "<a href=\"$qs$p=" . q($v) . "\">$v</a>"; // q() pretende sanear el dato
};

function q($d) { return htmlspecialchars($d); }     // para incluirlo en un URL

// FIN DE FUNCIONES

#'; eval($code); echo gpp($code);
// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
?>    

