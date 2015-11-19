<?php
$editing = true;
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
// if (!in_array(__FILE__, get_included_files())) { // we are being displayed directly
    require_once 'inc.php'; // header
    myInit(__FILE__);
}
#$code = '
require_once "funciones.php"; // para las funciones html

$sep = "|"; // separador para adm_add() y calcular_factura()

// hacer arrays aparecer como strings (para pasarlos en forms)

// añadir un value al final de un arraystring
function adm_add($as, $v) {   // array string, value
    global $sep;
    return strlen($as) > 0 ? "$as$sep$v" : $v;

}

// suponiendo los argumentos son arraystrings del mismo tamaño, devolver el HTML para la factura
function calcular_factura($articulos, $precios, $cantidades, $iva) { // 
    global $sep;

    $headings = ["articulo", "precio", "cantidad", "subtotal"];
    $datos = [];        // array de arrays de datos para table()

    if (strlen($articulos) > 0) {           // hay datos todavia?, por si acaso
        $aa = explode($sep, $articulos);    // articulos array
        $pa = explode($sep, $precios);      // precios array
        $ca = explode($sep, $cantidades);   // cantidades array
    
        $total = 0; 
        
        for ($i = 0; $i < count($aa); $i++) {
            $subtotal = $pa[$i] * $ca[$i];
            $total += $subtotal;
            array_push($datos, [$aa[$i], $pa[$i], $ca[$i], $subtotal]);  // añadimos el subtotal
        }
        $ti = $total * (1 + $iva);

        return "Factura:" . table($headings, $datos) . br() . 
            "Total: $total" . br() . 
            "Total ($iva de IVA incluido): $ti" . br() . br();
    } else
        return "No se debe nada (todavia).";
}

#'; eval($code); echo gpp($code);
// getting my URL
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    
    if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

