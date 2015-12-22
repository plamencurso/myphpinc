<?php
require_once "../inc.php"; // header
myInit(__FILE__);
#$code = '

require_once "../html.php";
require_once "db2.php";  // la version nueva con metadata

$yo = basename($_SERVER["SCRIPT_NAME"]);  // para enlaces relativos a este mismo script
$db_name = "discoteca";

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
$meta = db_open_meta();

echo "KEYS:", br();

foreach (["artistas", "albumes", "canciones", "videos"] as $t) {
    echo "$t PK: ",  db_getPK($meta, $db_name, $t), br();
    $ka = db_getFK($meta, $db_name, $t); if($ka[0]) echo "$t FK: " . $ka[1][0][0] . " ref tabla " . $ka[1][0][1] . " col " . $ka[1][0][2] . br();
    PC::db($ka, "$t");
    $rfk = db_getrFK($meta, $db_name, $t); if($rfk[0]) echo "Referencias a $t: ", table($rfk[0], $rfk[1]);
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
db_close($meta);

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
#'; eval($code); echo br(); highlight_string("<?php $code");

echo "</body</html>";
?>    

