<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
// if (!in_array(__FILE__, get_included_files())) { // we are being displayed directly
    require_once 'inc.php'; // header
    myInit(__FILE__);
}
//$code = '
// FUNCIONES HTML
// todos estos devuelven un string, supuestamente de HTML bien formado

// $t seria tag, $a - atributos del tag, $c - contenido del tag, $k - key, $v - value

function q($s) { return "\"" . htmlspecialchars($s) . "\""; } // finally some quoting

// comprobar si un atributo existe y añadirlo si no
function cattr($k, $v, $a = []) { if(!array_key_exists($k, $a)) $a[$k] = $v; return $a; } // devolvemos el array para poder encadenar varios como en form() ylnvinput() abajo

// función simple para emitir un elemento HTML, asi nos lo quitamos del medio lo antes posible (el HTML, no el elemento :)
function t($t, $c = "", $a = []) { // tag, contenidos opcionál (string o array de strings), attributos opcionales (assoc array)

    if (is_array($c)) // si es array de strings
        $c = "\n    " . implode("\n    ", $c) . "\n";  // intentamos formatearlo un poco

    $a = implode("", array_map(function($k) use($a) { return " $k" . (($v = $a[$k]) ? ("=" . q($v))  : ""); }, array_keys($a))); // esto puede sorprender si pasamos un valor de 0

    return "<$t$a" . ($c ? ">$c<" . "/$t>" : " />"); // tengo en cuenta el colorizador de Google

};

function tarray($tag, $arr = [], $a = []) {
    $res = "";   // el resultado en HTML
    foreach ($arr as $i) $res .= t($tag, $i, $a);
    return "$res\n";  // un poco de formato por favor
}

// ya que tenemos tarray(), hagamos tablas
// primeros dos argumentos $harr es headings array, $aarr es array de arrays de datos
// podriamos pasar 4 arrays de atributos - para TABLE TH TR y TD
// es un poco chapusa pero bueno, luego lo hago  bien :)

function table($harr, $aarr, $tablea = [], $tha = [], $tra = [], $tda = []) {
    $res = t("tr", tarray("th", $harr, $tha), $tra);       // aqui acumulamos el resultado parcial - los headings + rows

    foreach ($aarr as $arr) 
        $res .= t("tr", tarray("td", $arr, $tda), $tra); 

    return t("table", $res, $tablea);
}

// formularios

// form(name, action, contents)
// por defecto asume name=formulario action=mimismo method=GET
function form($c, $a = []) {return "\n" . 
    t("form", $c, 
        cattr("name", "formulario", 
        cattr("action", basename($_SERVER["SCRIPT_NAME"]),  // atributos por default 
        cattr("method", "GET", $a)))
    ) . "\n";
}

// funciones pequenitas

function br() { return t("br"); }
function h3($c) { return t("h3", $c); }
function h4($c) { return t("h4", $c); }

function input($ia) {return t("input", "", $ia); } 

// special(simple defaults) inputs
function linput($l, $a) { return t("label", $l) . input($a); } // labeled input
function lninput($n, $a = []) { return t("label", $n) . input(cattr("name", $n, $a)); } // labeled input with a default name=label
function lnvinput($n, $v, $a = []) { return t("label", $n) . input(cattr("name", $n, cattr("value", $v, $a))); } // labeled input with a default name=$n and value=$v

function submit($n, $v = "") {return input(["type" => "submit", "name" => $n, "value" => ($v ? $v : $n)]); }  // mecesita ser algo como lninput
function hidden($n, $v) {return input(["type" => "hidden", "name" => $n, "value" => $v]); }
function ahref($href, $t) { return t("a", $t, ["href" => $href]); }
//'; eval($code); 

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { // nos estan mostrando directamente, no incluyendo, muestra una pagina entera
    echo gpp($code); // muestra el codigo 

    // getting my URL

    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

