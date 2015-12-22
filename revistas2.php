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

$meta = db_open_meta();

if (!$d) {      // pedimos $d y $t si no estan

    // mostrar bases de datos
    $dbs = db_databases($meta);
    echo h3("Seleciona una base:") . ul(tarray("li", array_map("link_database", $dbs)));

} else if (!$t) {

    // mostrar tablas
    $tables = db_tables($meta, $d);
    echo h3("Seleciona una tabla:") . ul(tarray("li", array_map("link_table", $tables)));
    
} else {  // entrada principal

    $qs = "?d=$d&t=$t";     // guardamos los parametros para el query string (ahora veo que quise decir .= pero funciona asi tambien)
    
    $con = db_open($d);
    
    // si es solo para los nombres de las columnas ... el SELECT los devuelve
    // luego podemos necesitar mas cosas
    $cols = db_getColumns($meta, $d, $t);
    // PC::db($cols, "getcols");
    
    foreach ($cols[1] as $i) { $columnas[] = $i[0]; }

    // guardamos la clave primaria, debe estar exactamente una
    $pk = db_getPK($meta, $d, $t);
    
    // el index del PK en las columnas
    $pki = array_search($pk, $columnas);    // esto no debe fallar porque siempre necesitamos seleccionar el PK
    
    // claves, etc
    mostrar_informacion($t);
    
    // comandos de momento - i insertar, b borrar, a actualizar, pasamos el ID en id
    if ($c) {
        if($c == "i") {
    
            echo "Quiere insertar", br();
            
            // cojemos los valores pasados
            foreach ($columnas as $col) $valores[] = $_REQUEST[$col];
            $sql = "INSERT INTO $t (" . implode(",", $columnas) . ") VALUES (" . implode(",", array_map("comillas", $valores)) .  ")";
            echo $sql;
            db_query($con, $sql);
    
        } else if ($c == "b") {
    
            // Borrar
            param("id", $id);
            echo "Borrando $id ...", br();
            $sql = "DELETE FROM $t WHERE $pk = $id";
            echo $sql;
            db_query($con, $sql);
    
        } else if ($c == "a") {
    
            // Actualizar ... hm, para Borrar el ID es sufficiente pero no para Actualizar - habra que pasar todos los datos nuevos, o solo los cambiados, pero como?
            // haha, pasamos el ID en el atributo name de un submit, luego habra que buscarlo
            
    
            param("id", $id);
            echo "Quiere actualizar $id, no se puede todavía", br();
    
        } else die("Comando $c desconocido");
    }

    $datos = db_query($con, "SELECT * FROM $t ORDER BY 1");
    echo form(etable($datos, []));    // command = insert
    
}


//  FUNCIONES /////////////////////////////////////////////////////////////////////////////////


// hacer vinculo a base de datos
function link_database($d) {

    return ahref("?d=$d", $d);
}

// hacer vinculo a tabla
function link_table($t) {
    global $d;
    
    return ahref("?d=$d&t=$t", $t);
}

// mostrar información sobre claves, referencias, etc
function mostrar_informacion() {
    global $d, $t, $pk, $pki, $con, $meta;
    
    echo h3("Tabla $t");
    echo h4("Clave primaria: $pk con index $pki");
    echo h4("Claves forraneas:");

    $fka = db_getFK($meta, $d, $t);
    if ($fka[0]) {
        foreach ($fka[1] as $fk) {
            echo h5($fk[0] . " hace referencia a " . link_table($fk[1]) . "(" . $fk[2] . ")"); 
        }
    }
    
    echo h4("Referencias a esta tabla:");

    $rfka = db_getrFK($meta, $d, $t);
    PC::db($rfka, "rfka");
    if ($rfka[0]) {
        foreach ($rfka[1] as $rfk) {
            echo h5(link_table($rfk[1])  . "(" . $rfk[2] . ") hace referencia a " . $rfk[0]); 
        }
    }
}

// añadir commilas a valores para SQL
function comillas($s) {
    return "'" . str_replace("'", "''", $s) . "'";
}

// modificamos table() de html.php para añadir controles para editar
function etable($datos, $tablea = [], $tha = [], $tra = [], $tda = []) {
    global $d, $t, $columnas, $pki, $qs;

    $res = "\n" . t("tr", tarray("th", $columnas, $tha), $tra);       // aqui acumulamos el resultado parcial - los headings + rows

    // intentamos hacer una fila de edit controls
    $edits = "";
    foreach ($columnas as $i) $edits .= "\n" . td(input(["name" => $i]), $tda);
    $res .= tr($edits . td(submit("Insertar") . hidden("d", $d) . hidden("t", $t). hidden("c", "i"), $tda), $tra);

    // primero hacemos tr() y td() que ya se repite mucho t("tr", ...) ... ya están
    $i = 0;
    foreach ($datos[1] as $arr) {
//        PC::db($arr, "arr");

        // cojemos el valor del PK para esta fila
        $pkv = $arr[$pki];
        
        // añadimos los botones
        // $arr[] = submit("Actualizar", $pkv) . submit("Borrar", $pkv);
        
        // pues no van a ser botones que no hacen lo que necesito, probamos con vinculos
        $arr[] = ahref("$qs&c=a&id=$pkv", "Actualizar") . " " . ahref("$qs&c=b&id=$pkv", "Borrar");

        $res .= "\n" . tr(etarray("td", $arr, $tda), $tra); // podria hacer que etarray coja este "td" .. como nombre de función pero bueno

//        PC::db($arr, "arrb");
        $i++;       // el index de la fila
    };

    return t("table", $res, $tablea);
    
}

function etarray($tag, $arr = [], $a = []) {
    $res = "";   // el resultado en HTML
    foreach ($arr as $i) $res .= t($tag, $i, $a);
    return $res;
}



// VERSION ANTIGUA //////////////////////////////////////

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

