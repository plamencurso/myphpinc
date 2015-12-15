<?php

require_once "../html.php";

function db_open($db) {
    $con = mysqli_connect("localhost", "plamencurso", "", $db);
    if ($con === false) return db_connect_error();
    return $con;
}

function db_close($con) {
    return mysqli_close($con) || db_error($con);
}

function db_connect_error() {
    die("Connect error: #" . mysqli_connect_errno() . " - " . mysqli_connect_error());
}

function db_error($con) {
    die("Error: #" . mysqli_errno($con) . " - " . mysqli_error($con));
}

// ejecutar un query, devolver una tabla si hay datos(ver abajo el return)
function db_query($con, $q) {
    $res = mysqli_query($con, $q);

    if ($res === false) die(db_error($con));  // error
    if ($res === true) return "Success";
    
    // tenemos datos, mostrar
    $th = $tr = [];
    while($row = mysqli_fetch_assoc($res)) {
        $th = $th ? $th : array_keys($row);     // haz lo una sola vez
        $tr[] = array_values($row);
    }

    return [$th, $tr];     // en vez de devolver la tabla hecha, ahora devuelvo $h y 
}

?>