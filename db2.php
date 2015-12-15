<?php
require_once "../inc.php"; // header
myInit(__FILE__);

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

    return [$th, $tr];     // en vez de devolver la tabla hecha, ahora devuelvo headings y data
}

// metadata functions

function db_getPK($db, $table) {

    $metac = db_open("information_schema");

    // esto assume que la tabla tiene exactamente un PRIMARY KEY y no comprueba nada
    $pk = db_query($metac, "
        SELECT column_name 
        FROM key_column_usage 
        WHERE constraint_schema  = '$db' AND table_name = '$table' AND constraint_name = 'PRIMARY'");

    db_close($metac);
    return $pk[1][0][0];
}

function db_getFK($db, $table) {

    $metac = db_open("information_schema");

    // esto assume que la tabla tiene exactamente un FOREIGN KEY y no comprueba nada
    $fk = db_query($metac, "
        SELECT column_name, referenced_table_name, referenced_column_name
        FROM key_column_usage 
        WHERE constraint_schema  = '$db' AND table_name = '$table' AND referenced_table_name IS NOT NULL
    ");

    db_close($metac);
    return $fk;
}

// get reverse FK (las que se referien a nosotros)
function db_getrFK($db, $table) {

    $metac = db_open("information_schema");

    // esto assume que la tabla esta referida por exactamente un FOREIGN KEY y no comprueba nada
    $fk = db_query($metac, "
        SELECT referenced_column_name, table_name, column_name
        FROM key_column_usage 
        WHERE constraint_schema  = '$db' AND referenced_table_name = '$table'
    ");

    db_close($metac);
    return $fk;
}

?>