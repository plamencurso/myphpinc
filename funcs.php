<?php

// FUNCIONES HTML
// $t seria tag, $a - atributos del tag, $c - contenido del tag

// función simple para emitir un elemento HTML, asi nos lo quitamos del medio lo antes posible (el HTML, no el elemento :)
function t($t, $c = "", $a = []) { // tag, optional contents(string or array of strings), optional attributes(assoc array)

    if (is_array($c)) // si es array de tags(strings)
        $c = implode("\n    ", $c) . "\n";  // intentamos formatearlo un poco

    $a = implode("", array_map(function($k) use($a) { return " $k" . (($v = $a[$k]) ? "=$v" : ""); }, array_keys($a))); // implode $a, falta quoting de los values

    return "<$t$a" . ($c ? ">$c<" . "/$t>" : " />"); // tengo en cuenta el colorizador de Google por si acaso

};

function br() { return t("br"); }
function h3($c) { return t("h3", $c); }
function form($n, $act, $c) { return t("form", $c, ["name" => $n, "action" => $act, "method" => "GET"]);} // habra que cambiar esto para POST, entonces faltaria enctype
function input($ia) {return t("input", "", $ia); } 
function linput($l, $ia) { return t("label", $l) . input($ia); } // labeled input
function submit($n, $v) {return input(["type" => "submit", "name" => $n, "value" => $v]); }
function hidden($n, $v) {return input(["type" => "hidden", "name" => $n, "value" => $v]); }

?>    

