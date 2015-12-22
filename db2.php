<?php
#require_once "../inc.php"; // header
#myInit(__FILE__);

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

function db_open_meta() { return db_open("information_schema"); }
function db_close_meta($metac) { return db_close($metac); }

// informacion sobre las columnas que necesitemos, de momento el nombre solo
function db_getColumns($metac, $db, $table) {
    $cols = db_query($metac, "SELECT column_name FROM columns WHERE table_schema = '$db' AND table_name = '$table'");

    return $cols; 
}

function db_getPK($metac, $db, $table) {

    // esto devolvera todos PRIMARYs
    $pk = db_query($metac, "
        SELECT column_name 
        FROM key_column_usage 
        WHERE constraint_schema  = '$db' AND table_name = '$table' AND constraint_name = 'PRIMARY'");

    return $pk[1][0][0]; // aqui devolvemos solo el primero, hay que cambiarlo, pero va a romper a aritstas_etc4
}

function db_getFK($metac, $db, $table) {
    $fk = db_query($metac, "
        SELECT column_name, referenced_table_name, referenced_column_name
        FROM key_column_usage 
        WHERE constraint_schema  = '$db' AND table_name = '$table' AND referenced_table_name IS NOT NULL
    ");

    return $fk;
}

// get reverse FK (las que se referien a nosotros)
function db_getrFK($metac, $db, $table) {
    $fk = db_query($metac, "
        SELECT referenced_column_name, table_name, column_name
        FROM key_column_usage 
        WHERE constraint_schema  = '$db' AND referenced_table_name = '$table'
    ");

    return $fk;
}

// get a list of databases
function db_databases($metac) {
    $dbs = db_query($metac, "SELECT schema_name FROM schemata ORDER BY 1");
    
    return array_map(function($a) { return $a[0]; }, $dbs[1]);
}

// get a list of tables
function db_tables($metac, $db) {
    $dbs = db_query($metac, "SELECT table_name FROM tables WHERE table_schema = '$db' ORDER BY 1");
    
    return array_map(function($a) { return $a[0]; }, $dbs[1]);
}


?>