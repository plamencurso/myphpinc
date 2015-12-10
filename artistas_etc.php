<?php
require_once "../inc.php"; // header
myInit(__FILE__);
#$code = '

require_once "../html.php";
require_once "db.php";

$yo = basename($_SERVER["SCRIPT_NAME"]);  // para enlaces relativos a este mismo script

// son 2 relaciones 1 a N - artistas -> albumes -> canciones

// PROGRAMA PRINCIPAL

// usa unordered lists, se puede presentar algo mejor con un poco de CSS

// los parametros del script: co(ntinente) pa(is) ci(udad)
param("ar", $ar);     // param() cambia(defina) el valor de la variable(global) 2º argumento
param("al", $al);     // mira lo que hace extract(), todavía no lo uso aquí
param("ca", $ca);     // seria buena idea si son muchos parametros

// empezamos el query string para pasarlo a hacer_lista()
$qs = "$yo";

$con = db_open("discoteca");

// cojemos los artistas
$artistas = db_query($con, "SELECT id_artista, nombre FROM artistas ORDER BY nombre")[1]; // 0 son headings

// mostrar siempre los artistas
echo hacer_lista("Artistas", "", "?ar", $artistas);

if ($ar) {       // mostrar albumes si se ha selecionado un artista
    $qs .= "?ar=$ar";
    
    // cojemos los albumes
    $albumes = db_query($con, "SELECT id_album, titulo_album FROM albumes WHERE id_artista=$ar ORDER BY titulo_album")[1]; // 0 son headings

    echo hacer_lista("Álbumes", " de artista $ar", "&al", $albumes);
    
    if ($al) {       // mostrar canciones si se ha selecionado un album
        $qs .= "&al=$al";
        
        // cojemos las canciones
        $canciones = db_query($con, "SELECT id_cancion, titulo_cancion FROM canciones WHERE id_album=$al ORDER BY titulo_cancion")[1]; // 0 son headings

        echo hacer_lista("Canciones", " de album $al", "&ca", $canciones);
        
        if ($ca) {
            $videos = db_query($con, "SELECT tipo_video, enlace FROM videos WHERE id_cancion=$ca ORDER BY 1, 2");
            if($videos)
                echo "Videos: de canción$ca", table(["Tipo", "Enlace"], $videos[1]);
            else echo "No encuentro videos";
        }
    }
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
#'; eval($code); echo br(); highlight_string("<?php $code");

echo "</body</html>";
?>    

