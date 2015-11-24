<?php
$editing = true;  // do not include disqus
include_once "inc.php"; // header
myInit(__FILE__);
$code = '
require_once "html.php";

// Mis datos antiguos (del Viernes este ... :)
$continentes = ["Europa", "Asia"];

$paises["Europa"] = ["España", "Portugal"];
$ciudades["España"] = ["Madrid", "Barcelona", "Ciudad Real"];
$ciudades["Portugal"] = ["Lisboa", "Porto"];

$paises["Asia"] = ["China", "India"];
$ciudades["China"] = ["Beijing", "Shanghai"];
$ciudades["India"] = ["Mumbai", "Calcutta"];

$lcpc = [];                 // formato bueno para la función table()
$ul = "<ul>Continentes:\n";    // acumulamos una lista para mostrar luego

// transformamos los datos de arriba a un array cpc[continente][pais][ciudad] con valores de 1
foreach ($continentes as $co) {
    // ya que estamos aquí podemos hacer una lista para mostrar luego
    $ul .= "<li>$co</li>\n  Paises:<ul>\n";                // abrimos una lista para los paises 

    foreach($paises[$co] as $pa) {
        $ul .= "    <li>$pa</li>\n    Ciudades:<ul>\n";          // abrimos una lista para las ciudades 

        foreach($ciudades[$pa] as $ci) {
            $ul .= "      <li>$ci</li>\n";
            $cpc[$co][$pa][$ci] = 1;                // en este punto tenemos los 3 datos, se puede hacer de cualquier forma
            $lcpc[] = [$co, $pa, $ci];              // forma apta para table()
        };
        $ul .= "    </ul>\n";                             // cerramos las listas abiertas                           
    };
    $ul .= "  </ul>\n";
};
$ul .= "</ul>\n";

// a ver que lista me sale, temo lo peor :)
echo $ul;

// a ver si puedo mostrar $cpc con table() que necesita array de arrays
echo table(["Continente", "País", "Ciudad"], $lcpc);   // si, puedo :)

'; eval($code); highlight_string("<?php $code ?>");

// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
?> 
