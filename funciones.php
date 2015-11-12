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
    "cubo" =>       ["area" => function ($a) {return 6 * $a * $a;}, "perimetro" => function($a) {return 12 * $a;}, "volumen" => function($a) {return $a * $a * $a;}], 
];

// FUNCIONES HTML
// $t seria tag, $a - atributos del tag, $c - contenido del tag

// función simple para emitir un elemento HTML, asi nos lo quitamos del medio lo antes posible (el HTML, no el elemento :)
function t($t, $c = "", $a = []) { // tag, optional contents(string or array of strings), optional attributes(assoc array)

    if (is_array($c)) // si es array de tags(strings)
        $c = implode("\n    ", $c) . "\n";  // intentamos formatearlo un poco

    $a = implode("", array_map(function($k) use($a) { return " $k" . (($v = $a[$k]) ? "=$v" : ""); }, array_keys($a))); // implode $a, falta quoting de los values

    return "<$t$a" . ($c ? ">$c<" . "/$t>" : " />"); // tengo en cuenta el colorizador de Google por si acaso

};

function br() { return t("br"); }
function h3($c) { return t("h3", $c); }
function h4($c) { return t("h4", $c); }
function form($n, $act, $c) { return t("form", $c, ["name" => $n, "action" => $act, "method" => "GET"]);} // habra que cambiar esto para POST, entonces faltaria enctype
function input($ia) {return t("input", "", $ia); } 
function linput($l, $ia) { return t("label", $l) . input($ia); } // labeled input
function submit($n, $v) {return input(["type" => "submit", "name" => $n, "value" => $v]); }
function hidden($n, $v) {return input(["type" => "hidden", "name" => $n, "value" => $v]); }
function ahref($href, $t) { return t("a", $t, ["href" => $href]); }
'; eval($code); 

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
// if (!in_array(__FILE__, get_included_files())) { // we are being displayed directly
    echo gpp($code); // this belongs here

    // getting my URL

    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

