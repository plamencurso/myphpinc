<?php

include 'inc.php';
myInit(__FILE__);

include "Parsedown.php";
include "ParsedownExtra.php";

$Parsedown = new Parsedown();
$Extra = new ParsedownExtra();

// echo $Parsedown->text('Hello _Parsedown_!'); # prints: <p>Hello <em>Parsedown</em>!</p>
// echo $Parsedown->text('`$var = "val";`'); 


$Extra = new ParsedownExtra();

// echo $Extra->text('# Header {.sth}'); # prints: <h1 class="sth">Header</h1>

/* Cómo se define una función
function nombreFuncion() {
    // Lineas de código a ejecutar
}

Cómo llamamos a una función
nombreFuncion();


function saludo() {
    echo "Hola, te estoy llamando desde una función<br />";
}

saludo();
echo "Hola, te estoy llamando desde fuera de la función<br />";
saludo();
echo "Hola, te estoy llamando desde fuera de la función<br />";
saludo();
echo "Hola, te estoy llamando desde fuera de la función<br />";


function sumar($a, $b) {
    global $resultado;
    $resultado = $a + $b;
    echo "Dentro de la función: $resultado";
}
sumar(16, 9);

sumar(7, 8);
echo "<br />";

function sumar2($a, $b) {
    $resultado = $a + $b;
    return $resultado;
}

sumar2(100, 100);
*/

// probamos Markdown

$code = '

// area(r, a, b) - si r es verdad, calcula cuadrado/rectangulo
// en caso contrario - triangulo o rombo
// en caso de cuadrado se puede omitir b

echo "<h2> Muy simple aqui</h2>";

function area($rect, $n1, $n2 = 0) {
    if ($n2 == 0) $n2 = $n1; // cuadrado
    $a = $n1 * $n2;
    return $rect ? $a : $a / 2;
}

// cuadrado
echo "cuadrado 2 : " . area(true, 2) . "<br />";  
// rectangulo
echo "rectangulo 2 x 3 :" . area(true, 2, 3) . "<br />";  
// triangulo
echo "triangulo 5 4: " . area(false, 5, 4) . "<br />";  
// rombo
echo "rombo 6 7 : " . area(false, 6, 7) . "<br />";  

';

// echo md("```javascript" . $code . "```");
echo gpp($code);
eval($code);

echo "<hr/>";

$code = '
// intentamos de otra manera, parecida a objetos
echo "<h2> tambien queria probar así (muy preliminar, falta la matemática)</h2>";

function area51($tipo, $n1, $n2 = 0, $n3 = 0) {
    global $funcs;
    return $funcs[$tipo][0]($n1, $n2, $n3);
}

function perimetro51($tipo, $n1, $n2 = 0, $n3 = 0) {
    global $funcs;
    return $funcs[$tipo][1]($n1, $n2, $n3);
}

function volumen51($tipo, $n1, $n2 = 0, $n3 = 0) {
    global $funcs;
    return $funcs[$tipo][2]($n1, $n2, $n3);
}

$funcs = [ // lista de tuplas de "tipo", [areaFunc, perimetroFunc, volumenFunc]
    "cuadrado" =>   [function($a) {return $a * $a;}, function($a) {return 4 * $a;}], // cuadrado de a 
    "rectangulo" => [function($a, $b) {return $a * $b;}, function($a, $b) {return 2 * ($a + $b);}], // rectangulo de a b 
    "triangulo" =>  [function($a, $h) {return $a * $h / 2;}, function($a, $h) {$b = sqrt($a*$a/4 + $h*$h); return $a + $b * 2;}], // triángulo de a h (equilateral) perimetro esta mal?
    "rombo" =>      [function($d1, $d2) {return $d1 * $d2;}, function($d1, $d2) {return sqrt($d1*$d1 + $d2*$d2);}], // rombo de d1 d2 (equitodo tambien)
    "circulo" =>    [function($r) {return pi() * $r * $r;}, function($r) {return 2 * pi() * $r;}], // circulo de r 
    "trapecio" => [function($a, $b, $h) {return ($a + $b) * $h / 2;}, function($a, $b, $h) {$d = $a - $b; return $a + $b + 2 * sqrt ($d*$d + $h*$h);}], // rectangulo de a b 
    "cubo" => [function ($a) {return 6 * area51("cuadrado", $a);}, function($a) {return 12 * $a;}, function($a) {return $a * $a * $a;}], 
];

$pruebas = [ // lista de pruebas, los 0s los pongo para que no me salten NOTICIAS por indefinido
    ["cuadrado", 100, 0, 0],
    ["rectangulo", 100, 200, 0],
    ["triangulo", 100, 100, 0],
    ["rombo", 100, 100, 0],
    ["circulo", 100, 0, 0],
    ["trapecio", 200, 100, 100],
];

// PC::db($funcs, "funcs");
// PC::db($pruebas, "pruebas");

// probamos
foreach ($pruebas as $ps) {
    $tipo = $ps[0]; $n1 = $ps[1]; $n2 = $ps[2]; $n3 = $ps[3];
    echo "area51 de $tipo de $n1 $n2 $n3 = " . area51($tipo, $n1, $n2, $n3) . "<br/>";    
    echo "perimetro51 de $tipo de $n1 $n2 $n3 = " . perimetro51($tipo, $n1, $n2, $n3) . "<br/><br/>";    
}

// prueba cubo de $a
$a = 3;
echo "cubo de $a: area: " . area51("cubo", $a) . ", perimetro:" . perimetro51("cubo", $a) . ", volumen; " . volumen51("cubo", $a); 

'; echo gpp($code); eval($code);

$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";
echo disqus_code($yo, __FILE__, "cursomm");

?>    

</body>
</html>

