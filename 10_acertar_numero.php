
<?php

include 'inc.php';
myInit(__FILE__);

$l = 10;                        // limite numeros
$maxat = round(log($l, 2) + 1 );// limite intentos
PC::db("maxat: $maxat");

$yo = basename(__FILE__); // nuestro nombre para action and link URLs
$reset = true;

if (isset($_GET['numero'])) { // tenemos datos, procesamos
    $numero = $_GET['numero'];
    $aleatorio = $_GET['rxz'];
    $intentos = $_GET['nat'] - 1; // decrementamos intentos

    // podemos seguir? 
    if ($intentos > 0 && $numero != $aleatorio) { // si, seguimos

        echo "Mi número es " . ($aleatorio > $numero ? "mayor" : "menor") . " que $numero";
        $reset = false;

    } else // no podemos, hay que resetear, aver porque

         echo $numero == $aleatorio ? "Eres $intentos monstruos!" : "Numero de intentos excedido";

};

if ($reset) {
    $intentos = $maxat;
    $aleatorio = rand(1, $l);
    PC::db("aleatorio: $aleatorio");        
};

echo "<br /> te quedan $intentos intentos <br />
<form name='formulario' action ='$yo' method='GET' enctype='application/x-www-form-urlencoded'>
<label>Introduce un número entre 1 y $l<label>
<input type='number' name='numero'>
<input type='hidden' name='nat' value='$intentos'>
<input type='hidden' name='rxz' value='$aleatorio'>
<br />
<input type='submit' name='enviar_btn' value='Comprobar'/>
</form>
<a href='$yo'>Reset</a>
";



?>    

</body>
</html>
