<?php
$editing = false;  // do not include disqus

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
// if (!in_array(__FILE__, get_included_files())) { // we are being displayed directly
    require_once 'inc.php'; // header
    myInit(__FILE__);
}
// FUNCIONES HTML
// $t seria tag, $a - atributos del tag, $c - contenido del tag, $k - key, $v - value

// función simple para emitir un elemento HTML, asi nos lo quitamos del medio lo antes posible (el HTML, no el elemento :)
function t($t, $c = "", $a = []) { // tag, optional contents(string or array of strings), optional attributes(assoc array)

    if (is_array($c)) // si es array de tags(strings)
        $c = "\n    " . implode("\n    ", $c) . "\n";  // intentamos formatearlo un poco

    $a = implode("", array_map(function($k) use($a) { return " $k" . (($v = $a[$k]) ? ("=" . q($v))  : ""); }, array_keys($a))); // esto puede fallar si pasamos un valor de 0

    return "<$t$a" . ($c ? ">$c<" . "/$t>" : " />"); // tengo en cuenta el colorizador de Google

};

// todos estos devuelven un string, supuestamente de HTML bien formado

function q($s) { return "\"" . htmlspecialchars($s) . "\""; } // finally some quoting
// check for an attribute and add it if necessary
function cattr($k, $v, $a = []) { if(!array_key_exists($k, $a)) $a[$k] = $v; return $a; } // returning $a allows nesting, like in lvninput() below


function br() { return t("br"); }
function h3($c) { return t("h3", $c); }
function h4($c) { return t("h4", $c); }

// formularios

// form(name, action, contents)
// por defecto asume name=formulario action=mimismo 
function form($c, $a = []) {return "\n" . 
    t("form", $c, 
        cattr("name", "formulario", 
        cattr("action", basename($_SERVER["SCRIPT_NAME"]),  // atributos por default 
        cattr("method", "GET", $a)))
    ) . "\n";
} // habra que cambiar esto para POST, entonces faltaria enctype?

function input($ia) {return t("input", "", $ia); } 

// special(simple defaults) inputs
function linput($l, $a) { return t("label", $l) . input($a); } // labeled input
function lninput($n, $a = []) { return t("label", $n) . input(cattr("name", $n, $a)); } // labeled input with a default name=label
function lnvinput($n, $v, $a = []) { return t("label", $n) . input(cattr("name", $n, cattr("value", $v, $a))); } // labeled input with a default name=$n and value=$v

function submit($n, $v = "", $a = []) {return input(["type" => "submit", "name" => $n, "value" => ($v ? $v : $n)]); }  // mecesita ser algo como lninput
function hidden($n, $v, $a = []) {return input(["type" => "hidden", "name" => $n, "value" => $v]); }
function ahref($href, $t, $a = []) { return t("a", $t, ["href" => $href]); }

// lists
function li($i, $a = []) { return t("li", $i, $a); }
function ul($c, $a = []) { return t("ul", array_map("li", $c), $a); } // generate Unordered List from an array of strings
    
//'; eval($code); 

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { // nos estan mostrando directamente, no incluyendo, muestra una pagina entera
    echo gpp($code); // muestra el codigo 

    // getting my URL

    $yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
    include_once "incf.php";
    if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
}
?>    

