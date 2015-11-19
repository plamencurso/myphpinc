<?php
include "inc.php"; // header
myInit(__FILE__);
PC::db((new Tidy)->getConfig(), "Tidy");

#$code = '
include_once "funciones.php";   // funciones matematicas (y HTML)
include_once "admin.php";       // funciones administrativas
$editing = true;    

$math_label = "Matemáticas";
$adm_label = "Administrativas";
$fma_action = "";  // una de las dos arriba

// los datos administrativos
$fma_articulo = $fma_precio = $fma_cantidad = $fma_iva = "";  // luego solo se guarda la IVA en el formulario
$fma_articulos = $fma_precios = $fma_cantidades = "";         // estos se acumulan y guardan

if($_REQUEST) {  // tenemos datos
    // extraemos nuestros datos
    extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_OVERWRITE, "fma");  // los datos van a $fma_action, $fma_etc
}

// formulario de inicio
echo form([
    submit("action", $math_label),
    submit("action", $adm_label),
    hidden("fma_action", $fma_action)
]);

if($_REQUEST) {
    if ($fma_action == $math_label) {
        
        // INICIO PAGINA FUNCIONES MATEMATICAS
        echo h3("Funciones $math_label");
        
        if($fma_figura) { // si ya tenemos datos
            echo "quiere calcular $fma_funcion de $fma_figura con parametro(s) $fma_n1 $fma_n2 $fma_n3 ..." . br();
    
            $f = $funciones[$fma_figura][$fma_funcion]; // buscamos la funcion (usar array_key_exists() mejor)
            
            if ($f !== null) // la hemos encontrado
                echo "$fma_funcion de $fma_figura de $fma_n1 $fma_n2 $fma_n3: " . $f($fma_n1, $fma_n2, $fma_n3); // y la usamos
            else {
                echo "lo siento, no se como se hace esto todavía", br(), "sé calcular estos:", br();
                foreach ($funciones as $fig => $funcs)
                    echo implode(array_keys($funcs), ","), " de $fig", br();
            }
        }
        echo h4("Que desea?");
        
        echo form([                     // mostramos siempre el formulario, procesamos en el mismo archivo (por defecto)
            lnvinput("figura", $fma_figura, ["autofocus" => ""]),
            lnvinput("funcion", $fma_funcion),
            lnvinput("n1", $fma_n1 + 1),    // con esto podemos seguir pulsando a Entrar y nos va aumentando    
            lnvinput("n2", $fma_n2),        // así guardarando los valores del formulario o introduciendo diferentes
            lnvinput("n3", $fma_n3),        // podemos usar esto para decir -- muestra me lo del cubo mayor que este -- poniendo $fma_n1 + 1 
            hidden("action", $fma_action),  // guardamos esto para volver al apartado de matematicas
            submit("enviar"),
        //    rst("reset"),                 // resetea a los ultimos valores asi que no me vale
        ]
        // , ["method" => "POST"]           // descomentamos esta linia si queremos POST, form() hace GET por defecto
        );

        // FIN PAGINA FUNCIONES MATEMATICAS

    } else if ($fma_action == $adm_label) {

        // INICIO PAGINA FUNCIONES ADMINISTRATIVAS
        
        echo h3("Funciones $adm_label");
        
        echo calcular_factura(
            $na = adm_add($fma_articulos, $fma_articulo),
            $np = adm_add($fma_precios, $fma_precio),           // acumulamos los pedidos
            $nc = adm_add($fma_cantidades, $fma_cantidad),
            $ni = $fma_iva                                     // y guardamos la IVA
        );
        
        echo form([
            lninput("articulo", ["autofocus" => ""]),
            lninput("precio"),
            lninput("cantidad"),
            lnvinput("iva", $ni),
            submit("afactura", "Añadir"),
            
            // los otros datos que actualizamos y guardamos para la proxima
            hidden("articulos", $na),
            hidden("precios", $np),
            hidden("cantidades", $nc),
            hidden("action", $fma_action),  // guardamos esto para volver al apartado de administración
        ]);
        
        // FIN PAGINA FUNCIONES ADMINISTRATIVAS
    
    } else echo h3("se me ha perdido la acción por el camino, tengo esto: $fma_action");
}


#'; eval($code); echo gpp($code);
// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include_once "incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
?>    

