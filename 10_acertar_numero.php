
<?php

include 'inc.php';  // estas 2 líneas para debug 
myInit(__FILE__);


$yo = basename(__FILE__);       // nuestro nombre para action and link URLs

$l = 10;           // limite numeros
$reset = true;

if (isset($_GET['enviar_btn'])) {   // tenemos datos, procesamos

    $numero = $_GET['numero'];
    $aleatorio = $_GET['rxz'];
    $intentos = $_GET['nat'] - 1; // decrementamos intentos
    $l = $_GET['limite'];

     // debemos seguir preguntando? 
    if ($numero != $aleatorio && $intentos > 0) { // si, seguimos

        echo "Mi número es " . ($aleatorio > $numero ? "mayor" : "menor") . " que $numero";
        $reset = false;

    } else // no debemos, hay que resetear, decimos porque

         echo $numero == $aleatorio ? "Eres $intentos monstruos! Otro juego?" : "Numero de intentos excedido";
};

// supuestamente no se pueden pulsar 2 botones a la vez (no es verdad)
if (isset($_GET['cl_btn'])) {   // cambiamos el limite?
    $l = $_GET['limite'];
    $reset = true;  // descartamos intentos previos
};

$maxat = round(log($l, 2) + 1 );// limite intentos
PC::db("maxat: $maxat");        // para verlo en la consola de Chrome y también como un floater

if ($reset) {
    $intentos = $maxat;
    $aleatorio = rand(1, $l);
    $numero = "";
    PC::db("aleatorio: $aleatorio");        
};

// siempre mostramos el formulario
echo "<br /> te quedan $intentos intentos (de $maxat)<br />
<form name='formulario' action ='$yo' method='GET' enctype='application/x-www-form-urlencoded'>

<label>Introduce un número entre 1 y $l</label>
<input type='number' name='numero' value='$numero' autofocus onfocus=\"this.select()\">
<input type='hidden' name='rxz' value='$aleatorio'>
<input type='hidden' name='nat' value='$intentos'>
<input type='submit' name='enviar_btn' value='Comprobar'/>

<br />
<label>Cambiar el limite</label>
<input type='number' name='limite' value='$l' onfocus=\"this.select()\">
<input type='submit' name='cl_btn' value='Cambiar limite'/>

</form>
<a href='$yo'>Reset</a>
";

?>    

</body>
</html>
