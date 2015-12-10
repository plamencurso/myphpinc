<?php
require_once 'inc.php'; // header
require_once 'funciones.php';
myInit(__FILE__);
$editing = false;  // do not include disqus

$code = '
echo h3("usando " . ahref("funciones.php", "funciones.php"));

$f1_figura = $f1_funcion = $f1_n1 = $f1_n2 = $f1_n3 = ""; // esto vale solo la primera invocacion, luego lo coje del formulario

if ($_REQUEST) {            // tenemos datos, procesamos y mostramos, esto incluye GET POST y COOKIES?
                    
    // extraemos nuestros datos, si queremos solo POST, sustituimos REQUEST por POST, y miramos la invocacion de form() abajo
    extract($_REQUEST, EXTR_PREFIX_ALL, "f1");  // los datos van a $f1_figura, $f1_funcion, $f1_n1, $f1_n2 y $f1_n3      

    echo "quiere calcular $f1_funcion de $f1_figura con parametro(s) $f1_n1 $f1_n2 $f1_n3 ..." . br();
    
    $f = $funciones[$f1_figura][$f1_funcion]; // buscamos la funcion (usar array_key_exists() mejor)
    
    if ($f !== null) // la hemos encontrado
        echo "$f1_funcion de $f1_figura de $f1_n1 $f1_n2 $f1_n3: " . $f($f1_n1, $f1_n2, $f1_n3); // y la usamos
    else {
        echo "lo siento, no se como se hace esto todavía", br(), "sé calcular estos:", br();
        foreach ($funciones as $fig => $funcs)
            echo implode(array_keys($funcs), ","), " de $fig", br();
    }
}

echo h4("Que desea?");

echo form([                     // mostramos siempre el formulario, procesamos en el mismo archivo (por defecto)
    lnvinput("figura", $f1_figura, ["autofocus" => ""]),
    lnvinput("funcion", $f1_funcion),
    lnvinput("n1", $f1_n1 + 1),    // con esto podemos seguir pulsando a Entrar y nos va aumentando    
    lnvinput("n2", $f1_n2),        // así guardarando los valores del formulario o introduciendo diferentes
    lnvinput("n3", $f1_n3),        // podemos usar esto para decir -- muestra me lo del cubo mayor que este -- poniendo $f1_n1 + 1 
    submit("enviar"),
//    rst("reset"),                 // resetea a los ultimos valores asi que no me vale
]
// , ["method" => "POST"]           // descomentamos esta linia si queremos POST, form() hace GET por defecto
);

'; eval($code); highlight_string("<?php$code?>");

$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";
if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");

?>    

