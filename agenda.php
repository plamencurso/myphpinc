<?php
include 'inc.php';
myInit(__FILE__);
$code = '

// datos
$etiquetas = ["nombre", "teléfono", "email"];
$contactos = [ // [[nombre, teléfono, email ]]
    ["n1", "t2", "e3"],
    ["n3", "t1", "e2"],  // desorden
    ["n2", "t3", "e1"],
];

// programa principal
echo tag("h3", "Contactos (desordenados):");
echo table($etiquetas, $contactos);

// ordenamos por cada campo
for($orden = 0; $orden < count($etiquetas); $orden++) {
    echo tag("h3", "Contactos ordenados por: $etiquetas[$orden]");
    echo table($etiquetas, orderBy($contactos, $orden));
}
// fin de programa 

// funciones HTML

function tag($t, $s) { return "<$t>$s<" . "/$t>"; }  // tag algo (el colorizador javascript se come el closing tag si lo pongo juntos)

function tagArray($t, $a) {
    $s = "";
    foreach ($a as $i) { $s .= tag($t, $i); };
    return $s;
}

function table($ha, $aa) {      // headings array, data como array de arrays
    $t = "";
    foreach ($aa as $a) $t .= tag("tr", tagArray("td", $a));    // datos
    return tag("table", tagArray("th", $ha) . $t);              // etiquetas/column names/headings + datos
}

// funcion(es) pseudoSQL

function orderBy($aa, $i) { // ordenar un array de arrays por $a[$i]
    $ao = $aa;              // copiamos el array porque usort() lo modifica
    usort($ao, function($a, $b) use ($i) {return compare($a[$i], $b[$i]); });
    return $ao;
}

// no la encuentro en PHP?
function compare($a, $b) {
    return $a == $b ? 0 : $a < $b ? -1 : 1;
}
'; eval($code); echo gpp($code);

?>    

</body>
</html>

