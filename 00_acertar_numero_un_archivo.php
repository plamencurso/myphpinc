http://pastie.org/10518841
Aviso a navegantes!!!!!

El código funciona, pero lo vamos a mejorar...
Esta es una primera versión.
Jajaja


<?php
header('Content-Type: text/html; charset=UTF-8');
if(isset($_GET['enviar_btn'])){
	// Si entro, significa que he enviado los datos
	echo "Has accedido correctamente<br/>";
	if(isset($_GET['aleatorio'])){
		$aleatorio=$_GET['aleatorio'];
		echo "11111111 aleatorio vale: " . $aleatorio . "<br />";
	} else{
		$aleatorio=rand(1,100);
		echo "22222222 aleatorio vale: " . $aleatorio . "<br />";
	}
	$numero=$_GET['numero'];
	echo "Número: " . $numero . "<br />";



	if($numero==$aleatorio){
		echo "Has acertado... Enhorabuena!!!<br />";
		echo "<a href='00_acertar_numero_un_archivo.php'>Would you like to play again?</a>";
	} else {
		echo "
		<form name='formulario' action='00_acertar_numero_un_archivo.php' method='get' enctype='application/x-www-form-urlencoded'>
		<label>Introduce un número </label>
		<input type='number' name='numero' />
		<br />
		<input type='hidden' name='aleatorio' value='$aleatorio' />
		<input type='submit' name='enviar_btn' value='Comprobar' />
		</form>
		";
		echo "Inténtalo de nuevo...";
	}
	
} else {
	echo "
	<form name='formulario' action='00_acertar_numero_un_archivo.php' method='get' enctype='application/x-www-form-urlencoded'>
	<label>Introduce un número </label>
	<input type='number' name='numero' />
	<br />
	<input type='submit' name='enviar_btn' value='Comprobar' />
	</form>
	";	
}












?>