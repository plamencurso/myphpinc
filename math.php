<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
// if (!in_array(__FILE__, get_included_files())) { // we are being displayed directly
    require_once 'inc.php'; // header
    myInit(__FILE__);
}

//$code = '
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
//'; eval($code); 

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { // nos estan mostrando directamente, no incluyendo, muestra una pagina entera
    echo gpp($code); // muestra el codigo 

    // getting my URL

    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

