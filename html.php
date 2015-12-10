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
    return $res;
}

// ya que tenemos tarray(), hagamos tablas
// primeros dos argumentos $harr es headings array, $aarr es array de arrays de datos
// podriamos pasar 4 arrays de atributos - para TABLE TH TR y TD
// es un poco chapusa pero bueno, luego lo hago  bien :)

function table($harr, $aarr, $tablea = [], $tha = [], $tra = [], $tda = []) {
    $res = "\n" . t("tr", tarray("th", $harr, $tha), $tra);       // aqui acumulamos el resultado parcial - los headings + rows

    foreach ($aarr as $arr) 
        $res .= "\n" . t("tr", tarray("td", $arr, $tda), $tra); 

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

// default is POST to self
function formpost($c, $a = []) {return "\n" . 
    t("form", $c, 
        cattr("name", "formulario", 
        cattr("action", basename($_SERVER["SCRIPT_NAME"]),  // atributos por default 
        cattr("method", "POST", $a)))
    ) . "\n";
}


// funciones pequenitas

function b($s) { return t("b", $s); }  // bold
function h($s, $c = "red") { return t("font", $s, ["color" => $c]); }  // highlight

function br() { return t("br"); }
function h1($c) { return t("h1", $c); }
function h2($c) { return t("h2", $c); }
function h3($c) { return t("h3", $c); }
function h4($c) { return t("h4", $c); }
function h5($c) { return t("h5", $c); }
function h6($c) { return t("h6", $c); }

function input($ia) {return t("input", "", $ia); } 

// special(simple defaults) inputs
function linput($l, $a) { return t("label", $l) . input($a); } // labeled input
function lninput($l, $n, $a = []) { return t("label", $l) . input(cattr("name", $n, $a)); } // labeled input with a default name=label
function lnvinput($n, $v, $a = []) { return t("label", $n) . input(cattr("name", $n, cattr("value", $v, $a))); } // labeled input with a default name=$n and value=$v

function submit($n, $v = "") {return input(["type" => "submit", "name" => $n, "value" => ($v ? $v : $n)]); }  // mecesita ser algo como lninput
function hidden($n, $v) {return input(["type" => "hidden", "name" => $n, "value" => $v]); }
function ahref($href, $t) { return t("a", $t, ["href" => $href]); }

// otros

// mucho cuidao aquí, modificamos un parametro - $v
function param($p, &$v) {                               // prueba si tenemos el dato en _GET y lo asigna a $v, devuelve el valor actual o "" 
    if (isset($_GET) && array_key_exists($p, $_GET))    // la primera vez $_GET no esta ...
        return ($v = $_GET[$p]);                        // aqui modificamos el variable pasada, y devolvemos el valor también
    return "";  // no esta
}

function parampost($p, &$v) {                               // prueba si tenemos el dato en _GET y lo asigna a $v, devuelve el valor actual o "" 
    if (isset($_POST) && array_key_exists($p, $_POST))    // la primera vez $_GET no esta ...
        return ($v = $_POST[$p]);                        // aqui modificamos el variable pasada, y devolvemos el valor también
    return "";  // no esta
}


//'; eval($code); 

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { // nos estan mostrando directamente, no incluyendo, muestra una pagina entera
    echo gpp($code); // muestra el codigo 

    // getting my URL

    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

