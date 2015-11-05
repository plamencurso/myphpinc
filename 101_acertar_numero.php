<?php

include 'inc.php';  // estas 2 lneas para debug 
myInit(__FILE__);

$yo = basename(__FILE__);       // nuestro nombre para action and link URLs
$l = 10;           // limite numeros
$reset = true;

if (isset($_GET['enviar_btn'])) {   // tenemos datos, procesamos

    $numero = $_GET['numero'];
    $aleatorio = $_GET['rxz'];

    // debemos seguir preguntando? 
    if ($numero != $aleatorio) { // si, seguimos
        echo "Mi número es " . ($aleatorio > $numero ? "mayor" : "menor") . " que $numero";
        $reset = false;
    } else // no debemos, hay que resetear, decimos porque
        echo "Eres 3 monstruos! Jugamos otra vez!";
};

if ($reset) {
    $aleatorio = rand(1, $l);
    $numero = "";
    PC::db("aleatorio: $aleatorio");        
};

echo "
<form name='formulario' action ='$yo' method='GET' enctype='application/x-www-form-urlencoded'>
<label>Introduce un número entre 1 y $l</label>
<input type='number' name='numero' value='$numero' autofocus onfocus=\"this.select()\">
<input type='hidden' name='rxz' value='$aleatorio'>
<input type='submit' name='enviar_btn' value='Comprobar'/>
</form>
<a href='$yo'>Reset</a>
";
?>    

</body>
</html>
