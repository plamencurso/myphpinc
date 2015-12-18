<?php
require_once "../inc.php"; // header
myInit(__FILE__);
#$code = '

require_once "../html.php";
require_once "db2.php";  // la version nueva con metadata

$yo = basename($_SERVER["SCRIPT_NAME"]);  // para enlaces relativos a este mismo script
$db_name = "discoteca";

/*

A ver, que quiero hacer? - Backend para editar tablas
mostramos una tabla a la vez, primero con opcion de insertar, luego para editar, borrar, y seguir vinculos a tablas relacionadas
tambien tiene que asistir en añadir las entradas debiles necesarias

*/

// empezamos el query string para pasarlo a hacer_lista(), etc
$qs = "$yo?";

param("d", $d); // nombre de base de datos
param("t", $t); // nombre de la tabla
param("c", $c); // command

if (!$d or !$t) die("Faltan parametros");

$qs = "?d=$d&t=$t";     // guardamos los parametros para el query string

$con = db_open($d);
$meta = db_open_meta();

// si es solo para los nombres de las columnas ... el SELECT los devuelve
// luego podemos necesitar mas cosas
$cols = db_getColumns($meta, $d, $t);
// PC::db($cols, "getcols");

foreach ($cols[1] as $i) { $columnas[] = $i[0]; }
PC::db($columnas);

if ($c) {
    if($c == "i") {
        echo "Quiere insertar", br();
        
        // cojemos los valores pasados
        foreach ($columnas as $col) $valores[] = $_REQUEST[$col];
        
        PC::db($valores, "valores");
        
        $sql = "INSERT INTO $t (" . implode(",", $columnas) . ") VALUES (" . implode(",", array_map("comillas", $valores)) .  ")";
        echo $sql;
        db_query($con, $sql);
    } else if ($c == "b") {
        // Borrar
    } else die("Comando $c desconocido");
}


$datos = db_query($con, "SELECT * FROM $t ORDER BY 1");
echo form(etable($datos, []));    // command = insert


//  FUNCIONES

// añadir commilas a valores para SQL
function comillas($s) {
    return "'" . str_replace("'", "''", $s) . "'";
}

// modificamos table() de html.php para añadir controles para editar
function etable($datos, $tablea = [], $tha = [], $tra = [], $tda = []) {
    global $d, $t, $columnas;

    $res = "\n" . t("tr", tarray("th", $columnas, $tha), $tra);       // aqui acumulamos el resultado parcial - los headings + rows

    // intentamos hacer una fila de edit controls
    $edits = "";
    foreach ($columnas as $i) $edits .= "\n" . t("td", input(["name" => $i]), $tda);
    $res .= t("tr", $edits . t("td", submit("Insertar") . hidden("d", $d) . hidden("t", $t). hidden("c", "i"), $tda), $tra);

    foreach ($datos[1] as $arr) 
        $res .= "\n" . t("tr", tarray("td", $arr, $tda), $tra); 

    return t("table", $res, $tablea);
}


function etarray($tag, $arr = [], $a = []) {
    $res = "";   // el resultado en HTML
    foreach ($arr as $i) $res .= t($tag, $i, $a);
    return $res;
}


if (0) { // quitamos esto del medio de momento - lo de artistas_etc4.php

// son 3 relaciones 1 a N - artistas -> albumes -> canciones -> videos

// PROGRAMA PRINCIPAL

// usa unordered lists, se puede presentar algo mejor con un poco de CSS

// los parametros del script: co(ntinente) pa(is) ci(udad)
param("ar", $ar);     // param() cambia(defina) el valor de la variable(global) 2º argumento
param("al", $al);     // mira lo que hace extract(), todavía no lo uso aquí
param("ca", $ca);     // seria buena idea si son muchos parametros
param("vi", $vi);

// empezamos el query string para pasarlo a hacer_lista()
$qs = "$yo?";

$con = db_open($db_name);

echo "KEYS:", br();

foreach (["artistas", "albumes", "canciones", "videos"] as $t) {
    echo "$t PK: ",  db_getPK($db_name, $t), br();
    $ka = db_getFK($db_name, $t); if($ka[0]) echo "$t FK: " . $ka[1][0][0] . " ref tabla " . $ka[1][0][1] . " col " . $ka[1][0][2] . br();
    PC::db($ka, "$t");
    $rfk = db_getrFK($db_name, $t); if($rfk[0]) echo "Referencias a $t: ", table($rfk[0], $rfk[1]);
}

// empezamos por las canciones de momento, luego veremos que hacer con los videos

if ($ca) {
    // mostrar la cancion selecionada

    $qs .= "vi=$vi&";

    $videos = db_query($con, "SELECT tipo_video, enlace FROM videos WHERE id_cancion=$ca ORDER BY 1, 2")[1];
    
    if($videos)
        foreach ($videos as $v)
            echo br(), "$v[0]: $v[1]" .  t("video", t("source", "", ["src" => $v[1]]), ["width" => 320, "height" => 240, "controls" => ""]);
//          echo "Videos: de canción $ca", table(["Tipo", "Enlace"], $videos[1]);
        else echo "No encuentro videos";

    
} elseif ($al) {
    // mostrar el album seleccionado

    $qs .= "al=$al&";
    
    // cojemos las canciones
    $canciones = db_query($con, "SELECT id_cancion, titulo_cancion, num_pista FROM canciones WHERE id_album=$al ORDER BY num_pista")[1]; // 0 son headings

    echo hacer_lista("Canciones", " de album $al", "ca", $canciones);

} elseif ($ar) {
    // mostrar el artista seleccionado

    $qs .= "ar=$ar&";
    
    // cojemos los albumes
    $albumes = db_query($con, "SELECT id_album, titulo_album FROM albumes WHERE id_artista=$ar ORDER BY titulo_album")[1]; // 0 son headings

    echo hacer_lista("Álbumes", " de artista $ar", "al", $albumes);

} else {
    // nada selecionado, mostramos la lista de artistas

    // cojemos los artistas
    $artistas = db_query($con, "SELECT id_artista, nombre FROM artistas ORDER BY nombre")[1]; // 0 son headings

    echo hacer_lista("Artistas", "", "ar", $artistas);
}

db_close($con);

// FIN DE PROGRAMA PRINCIPAL

// FUNCIONES

// esta vez la lista de datos es array de [id, dato]
function hacer_lista($q, $dq, $p, $l) {     // hacer la lista de que deque, nombre de parametro, lista de datos
    $res = "$q$dq:<ul>";                    // inicio de unordered list, aqui acumulamos el resultado
    
    foreach ($l as $li) $res .= "<li>" . hacer_vinculo($p, $li[0], $li[1]) . "</li>";

    return "$res</ul>";
}

function hacer_vinculo($p, $v, $t) {           // parametro, value, texto 
    global $qs;

    return "<a href=\"$qs$p=$v\">$t</a>"; // q() pretende sanear el dato
};

// FIN DE FUNCIONES
}
#'; eval($code); echo br(); highlight_string("<?php $code");

echo "</body</html>";
?>    

