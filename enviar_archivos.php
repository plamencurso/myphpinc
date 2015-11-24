<?php
$editing = true;  // do not include disqus
require_once "../inc.php"; // header
myInit(__FILE__);
#$code = '
require_once("../html.php");

echo form([
    input(["type" => "file", "name" => "archivo_fls[]", "multiple" => ""]),
    submit("subir_btn")
], ["method" => "POST", "action" => "subir_archivos.php", "enctype" => "multipart/form-data"]);

#'; eval($code); highlight_string("<?php $code ? >");

// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
require_once("../incf.php");

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
echo "</body</html>";
?>    

