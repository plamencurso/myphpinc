<?php
$editing = true;  // do not include disqus
require_once "inc.php"; // header
myInit(__FILE__);
$code = '




echo "testing empty.php";




'; eval($code); highlight_string("<?php $code ?>");

// getting my URL
$yo = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
include "incf.php";

if (!$editing) echo disqus_code($yo, __FILE__, "cursomm");
echo "</body</html>";
?>    

