<?php
$editing = true;  // do not include disqus
include_once "inc.php"; // header
myInit(__FILE__);
#$code = '









#'; eval($code); echo gpp($code);
// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
?>    

