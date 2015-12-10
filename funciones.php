<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
// if (!in_array(__FILE__, get_included_files())) { // we are being displayed directly
    require_once 'inc.php'; // header
    myInit(__FILE__);
}
$code = '
// FUNCIONES matematicas
$funciones = [ // [figura => [nombreFuncion => funcion]]
    "cuadrado" =>   ["area" => function($a) {return $a * $a;}, "perimetro" => function($a) {return 4 * $a;}], // cuadrado de a 
    "rectangulo" => ["area" => function($a, $b) {return $a * $b;}, "perimetro" => function($a, $b) {return 2 * ($a + $b);}], // rectangulo de a b 
    "triangulo" =>  ["area" => function($a, $h) {return $a * $h / 2;}, "perimetro" => function($a, $h) {$b = sqrt($a*$a/4 + $h*$h); return $a + $b * 2;}], // triángulo de a h (equilateral) perimetro esta mal?
    "rombo" =>      ["area" => function($d1, $d2) {return $d1 * $d2;}, "perimetro" => function($d1, $d2) {return sqrt($d1*$d1 + $d2*$d2);}], // rombo de d1 d2 (equitodo tambien)
    "circulo" =>    ["area" => function($r) {return pi() * $r * $r;}, "perimetro" => function($r) {return 2 * pi() * $r;}], // circulo de r 
    "trapecio" =>   ["area" => function($a, $b, $h) {return ($a + $b) * $h / 2;}, "perimetro" => function($a, $b, $h) {$d = $a - $b; return $a + $b + 2 * sqrt ($d*$d + $h*$h);}], // rectangulo de a b 
    "cubo" =>       ["superficie" => function ($a) {return 6 * $a * $a;}, "perimetro" => function($a) {return 12 * $a;}, "volumen" => function($a) {return $a * $a * $a;}], 
];

// FUNCIONES HTML
// todos estos devuelven un string, supuestamente de HTML bien formado

// $t seria tag, $a - atributos del tag, $c - contenido del tag, $k - key, $v - value

function q($s) { return "\"" . htmlspecialchars($s) . "\""; } // finally some quoting

// check for an attribute and add it if necessary
function cattr($k, $v, $a = []) { if(!array_key_exists($k, $a)) $a[$k] = $v; return $a; } // returning $a allows nesting, like in form() and lnvinput() below

// función simple para emitir un elemento HTML, asi nos lo quitamos del medio lo antes posible (el HTML, no el elemento :)
function t($t, $c = "", $a = []) { // tag, optional contents(string or array of strings), optional attributes(assoc array)

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
'; eval($code); 

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { // nos estan mostrando directamente, no incluyendo, muestra una pagina entera
    highlight_string("<?php$code?>"); // muestra el codigo 

    // getting my URL

    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

