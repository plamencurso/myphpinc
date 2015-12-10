<?php
$editing = true;  // do not include disqus
require_once "../inc.php"; // header
myInit(__FILE__);
$code = '

// tipos y tamaños aceptables ttac[tipo] = tamaño
$ttac = [
    "image/jpeg" => 512 * 1024,
    "application/pdf" => 200 * 1024,
    "text/plain" => 100 * 1024,
];

$c = count($_FILES["archivo_fls"]["name"]);

for($i = 0; $i < $c; $i++) {
    echo "<br />File #" . ($i + 1). " de $c<br /><br />";

    $f = $_FILES["archivo_fls"];  // estos 2 variables los uso mucho
    $n = $f["name"][$i];

    foreach($f as $clave => $valor) { 
        echo "Propriedad: $clave ______ Valor: $valor[$i]<br />";    
    };

    echo "<br />";
    // movemos el archivo a nuesta carpeta
    $archivo = $f["tmp_name"][$i];
    $destino = "archivos/$n";

    if ($es = $f["error"][$i]) 

        echo "Erorr $es al recibir el archivo $n<br />";

    // comprobamos el tipo y el tamaño 
    else if (array_key_exists($t = $f["type"][$i], $ttac) && ($s = $f["size"][$i]) <= $ttac[$t] )  // aceptable

        if($e = move_uploaded_file($archivo, $destino)) 
            echo "Archivo $n subido correctamente";
        else
            echo "error $e al colocar el archivo $n";

    else {  // no aceptable
      
        echo "Sólo admito archivos de estos tipos y tamaños:<br/><br />";
        foreach($ttac as $tt => $ts)
            echo "tipo: $tt con tamaño hasta " . $ts/1024 . "K<br/>";
        echo "<br /> El archivo recibido ($n) es de tipo $t y tamaño " . ($s/1024) . "K";

    }
}


'; eval($code); highlight_string("<?php $code");

// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
require_once "../incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
echo "</body</html>";
?>    

